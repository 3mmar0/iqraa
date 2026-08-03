<?php

namespace App\Http\Controllers\Web\Student;

use App\Http\Controllers\Controller;
use App\Models\Assignment;
use App\Models\Lesson;
use App\Models\LessonProgress;
use App\Services\EnrollmentService;
use App\Services\LessonProgressService;
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
        $lesson->load(['course.instructor', 'mediaAssets', 'quiz']);
        abort_unless($this->enrollments->userHasActiveEnrollment($request->user(), $lesson->course_id), 403);

        $siblings = $lesson->course->lessons()->where('status', 'published')->orderBy('position')->get();
        $index = $siblings->search(fn ($item) => $item->id === $lesson->id);

        $completedLessonIds = LessonProgress::query()
            ->where('user_id', $request->user()->id)
            ->whereIn('lesson_id', $siblings->pluck('id'))
            ->where('status', 'completed')
            ->pluck('lesson_id')
            ->all();

        $isCompleted = in_array($lesson->id, $completedLessonIds, true);

        $assignments = Assignment::query()
            ->where('lesson_id', $lesson->id)
            ->where('status', 'published')
            ->orderByRaw('due_at is null')
            ->orderBy('due_at')
            ->get();

        $videos = $lesson->mediaAssets->where('type', 'video')->values();
        $files = $lesson->mediaAssets->where('type', '!=', 'video')->values();

        return view('student.lessons.show', [
            'lesson' => $lesson,
            'isCompleted' => $isCompleted,
            'previous' => $index !== false && $index > 0 ? $siblings[$index - 1] : null,
            'next' => $index !== false && $index < $siblings->count() - 1 ? $siblings[$index + 1] : null,
            'position' => $index !== false ? $index + 1 : null,
            'total' => $siblings->count(),
            'siblings' => $siblings,
            'completedLessonIds' => $completedLessonIds,
            'assignments' => $assignments,
            'videos' => $videos,
            'files' => $files,
        ]);
    }

    public function complete(Request $request, Lesson $lesson): RedirectResponse
    {
        abort_unless($this->enrollments->userHasActiveEnrollment($request->user(), $lesson->course_id), 403);
        $this->progress->markCompleted($request->user(), $lesson);

        return back()->with('status', 'تم تعليم الدرس كمكتمل.');
    }
}
