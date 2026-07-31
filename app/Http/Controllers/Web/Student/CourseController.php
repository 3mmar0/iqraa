<?php

namespace App\Http\Controllers\Web\Student;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\LessonProgress;
use App\Services\EnrollmentService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CourseController extends Controller
{
    public function __construct(private EnrollmentService $enrollments)
    {
    }

    public function index(Request $request): View
    {
        $courses = Enrollment::query()
            ->with(['course.instructor', 'course.lessons'])
            ->where('user_id', $request->user()->id)
            ->where('status', 'active')
            ->get()
            ->map(function (Enrollment $enrollment) use ($request) {
                $course = $enrollment->course;
                $lessonIds = $course->lessons->pluck('id');
                $completed = LessonProgress::query()
                    ->where('user_id', $request->user()->id)
                    ->whereIn('lesson_id', $lessonIds)
                    ->where('status', 'completed')
                    ->count();

                $course->completed_lessons_count = $completed;
                $course->lessons_count = $lessonIds->count();
                $course->progress_percent = $course->lessons_count > 0
                    ? round(($completed / $course->lessons_count) * 100)
                    : 0;

                return $course;
            });

        return view('student.courses.index', compact('courses'));
    }

    public function show(Request $request, Course $course): View
    {
        abort_unless($this->enrollments->userHasActiveEnrollment($request->user(), $course->id), 403);

        $course->load(['lessons', 'quizzes', 'instructor']);

        return view('student.courses.show', compact('course'));
    }
}
