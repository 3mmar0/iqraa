<?php

namespace App\Http\Controllers\Web\Student;

use App\Http\Controllers\Controller;
use App\Models\Lesson;
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
        $lesson->load(['course', 'mediaAssets']);
        abort_unless($this->enrollments->userHasActiveEnrollment($request->user(), $lesson->course_id), 403);

        $siblings = $lesson->course->lessons()->where('status', 'published')->orderBy('position')->get();
        $index = $siblings->search(fn ($item) => $item->id === $lesson->id);

        return view('student.lessons.show', [
            'lesson' => $lesson,
            'previous' => $index !== false && $index > 0 ? $siblings[$index - 1] : null,
            'next' => $index !== false && $index < $siblings->count() - 1 ? $siblings[$index + 1] : null,
        ]);
    }

    public function complete(Request $request, Lesson $lesson): RedirectResponse
    {
        abort_unless($this->enrollments->userHasActiveEnrollment($request->user(), $lesson->course_id), 403);
        $this->progress->markCompleted($request->user(), $lesson);

        return back()->with('status', 'تم تعليم الدرس كمكتمل.');
    }
}
