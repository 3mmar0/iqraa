<?php

namespace App\Http\Controllers\Web\Student;

use App\Http\Controllers\Controller;
use App\Models\Assignment;
use App\Models\Lesson;
use App\Models\QuizAttempt;
use App\Services\EnrollmentService;
use App\Services\LessonProgressService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LessonController extends Controller
{
    public function __construct(
        private EnrollmentService $enrollments,
        private LessonProgressService $progress,
    ) {
    }

    public function show(Request $request, Lesson $lesson): View
    {
        $lesson->load(['course.instructor', 'mediaAssets', 'quiz', 'mainMediaAsset']);
        abort_unless($this->enrollments->userHasActiveEnrollment($request->user(), $lesson->course_id), 403);
        abort_if($lesson->is_locked || $lesson->status !== 'published', 404);

        $siblings = $lesson->course->lessons()->where('status', 'published')->where('is_locked', false)->orderBy('position')->get();
        $index = $siblings->search(fn ($item) => $item->id === $lesson->id);

        $completedLessonIds = \App\Models\LessonProgress::query()
            ->where('user_id', $request->user()->id)
            ->whereIn('lesson_id', $siblings->pluck('id'))
            ->where('status', 'completed')
            ->pluck('lesson_id')
            ->all();

        $progressRow = $this->progress->progressFor($request->user(), $lesson);
        $isCompleted = in_array($lesson->id, $completedLessonIds, true);

        $mainVideo = $lesson->mainMediaAsset;
        if (! $mainVideo || $mainVideo->type !== 'video') {
            $mainVideo = $lesson->mediaAssets->firstWhere('type', 'video');
        }

        $files = $lesson->mediaAssets
            ->filter(fn ($asset) => ! $mainVideo || (int) $asset->id !== (int) $mainVideo->id)
            ->values();

        $secondaryVideos = $files->filter(
            fn ($asset) => $asset->type === 'video' || str_starts_with((string) $asset->mime, 'video/')
        )->values();

        $downloadableFiles = $files->reject(
            fn ($asset) => $asset->type === 'video' || str_starts_with((string) $asset->mime, 'video/')
        )->values();

        $quiz = $lesson->quiz && $lesson->quiz->status === 'published' ? $lesson->quiz : null;
        $examUnlocked = $quiz ? $this->progress->examIsUnlocked($request->user(), $lesson) : false;

        $latestAttempt = null;
        if ($quiz) {
            $latestAttempt = QuizAttempt::query()
                ->where('user_id', $request->user()->id)
                ->where('quiz_id', $quiz->id)
                ->where('status', 'submitted')
                ->latest('submitted_at')
                ->first();
        }

        $assignments = Assignment::query()
            ->where('lesson_id', $lesson->id)
            ->where('status', 'published')
            ->orderByRaw('due_at is null')
            ->orderBy('due_at')
            ->get();

        return view('student.lessons.show', [
            'lesson' => $lesson,
            'mainVideo' => $mainVideo,
            'contentHtml' => $lesson->content_html,
            'files' => $downloadableFiles,
            'secondaryVideos' => $secondaryVideos,
            'isCompleted' => $isCompleted,
            'progressRow' => $progressRow,
            'previous' => $index !== false && $index > 0 ? $siblings[$index - 1] : null,
            'next' => $index !== false && $index < $siblings->count() - 1 ? $siblings[$index + 1] : null,
            'position' => $index !== false ? $index + 1 : null,
            'total' => $siblings->count(),
            'siblings' => $siblings,
            'completedLessonIds' => $completedLessonIds,
            'assignments' => $assignments,
            'quiz' => $quiz,
            'examUnlocked' => $examUnlocked,
            'latestAttempt' => $latestAttempt,
        ]);
    }

    public function progress(Request $request, Lesson $lesson): JsonResponse|RedirectResponse
    {
        abort_unless($this->enrollments->userHasActiveEnrollment($request->user(), $lesson->course_id), 403);
        abort_if($lesson->is_locked || $lesson->status !== 'published', 404);

        $validated = $request->validate([
            'position_seconds' => ['nullable', 'integer', 'min:0'],
            'completed' => ['nullable', 'boolean'],
        ]);

        $row = $this->progress->upsertPosition(
            $request->user(),
            $lesson,
            (int) ($validated['position_seconds'] ?? 0),
            (bool) ($validated['completed'] ?? false)
        );

        $payload = [
            'last_position_seconds' => (int) $row->last_position_seconds,
            'video_completed' => $row->watchCompleted(),
            'exam_unlocked' => $this->progress->examIsUnlocked($request->user(), $lesson->fresh(['quiz', 'mainMediaAsset'])),
        ];

        if ($request->expectsJson() || $request->wantsJson() || $request->ajax()) {
            return response()->json($payload);
        }

        return back()->with('status', $payload['video_completed'] ? 'تم تسجيل إكمال مشاهدة الفيديو.' : 'تم حفظ موضع المشاهدة.');
    }

    public function complete(Request $request, Lesson $lesson): RedirectResponse
    {
        abort_unless($this->enrollments->userHasActiveEnrollment($request->user(), $lesson->course_id), 403);
        $this->progress->markCompleted($request->user(), $lesson);

        return back()->with('status', 'تم تعليم الدرس كمكتمل.');
    }
}
