<?php

namespace App\Http\Controllers\Web\Student;

use App\Http\Controllers\Controller;
use App\Models\Assignment;
use App\Models\CalendarEvent;
use App\Models\Course;
use App\Models\CourseAccessRequest;
use App\Models\Enrollment;
use App\Models\LessonProgress;
use App\Models\QuizAttempt;
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
        $user = $request->user();

        $courses = Enrollment::query()
            ->with(['course.instructor', 'course.lessons'])
            ->where('user_id', $user->id)
            ->where('status', 'active')
            ->latest('updated_at')
            ->get()
            ->map(function (Enrollment $enrollment) use ($user) {
                $course = $enrollment->course;
                $lessonIds = $course->lessons->pluck('id');
                $completed = LessonProgress::query()
                    ->where('user_id', $user->id)
                    ->whereIn('lesson_id', $lessonIds)
                    ->where('status', 'completed')
                    ->count();

                $course->completed_lessons_count = $completed;
                $course->lessons_count = $lessonIds->count();
                $course->progress_percent = $course->lessons_count > 0
                    ? (int) round(($completed / $course->lessons_count) * 100)
                    : 0;

                return $course;
            });

        $overallPercent = $courses->isNotEmpty()
            ? (int) round($courses->avg('progress_percent'))
            : 0;

        $inProgress = $courses->filter(fn ($c) => $c->progress_percent > 0 && $c->progress_percent < 100);
        $completedCourses = $courses->filter(fn ($c) => $c->progress_percent >= 100 && $c->lessons_count > 0);
        $notStarted = $courses->filter(fn ($c) => $c->progress_percent === 0 || $c->lessons_count === 0);

        $pendingRequests = CourseAccessRequest::query()
            ->with('course')
            ->where('user_id', $user->id)
            ->where('status', 'pending')
            ->latest()
            ->get();

        $enrolledIds = $courses->pluck('id')->all();
        $discoverCourses = Course::query()
            ->with('instructor')
            ->where('status', 'published')
            ->when($enrolledIds !== [], fn ($q) => $q->whereNotIn('id', $enrolledIds))
            ->latest('updated_at')
            ->limit(3)
            ->get();

        return view('student.courses.index', [
            'courses' => $courses,
            'overallPercent' => $overallPercent,
            'inProgress' => $inProgress,
            'completedCourses' => $completedCourses,
            'notStarted' => $notStarted,
            'pendingRequests' => $pendingRequests,
            'discoverCourses' => $discoverCourses,
        ]);
    }

    public function show(Request $request, Course $course): View
    {
        abort_unless($this->enrollments->userHasActiveEnrollment($request->user(), $course->id), 403);

        $user = $request->user();

        $course->load([
            'lessons' => fn ($q) => $q->where('status', 'published')->orderBy('position'),
            'quizzes' => fn ($q) => $q->where('status', 'published')->orderBy('title'),
            'instructor',
        ]);

        $lessonIds = $course->lessons->pluck('id');
        $completedLessonIds = LessonProgress::query()
            ->where('user_id', $user->id)
            ->whereIn('lesson_id', $lessonIds)
            ->where('status', 'completed')
            ->pluck('lesson_id')
            ->all();

        $lessonsCount = $lessonIds->count();
        $completedCount = count($completedLessonIds);
        $progressPercent = $lessonsCount > 0 ? (int) round(($completedCount / $lessonsCount) * 100) : 0;

        $continueLesson = $course->lessons->first(
            fn ($lesson) => ! in_array($lesson->id, $completedLessonIds, true)
        ) ?? $course->lessons->last();

        $quizIds = $course->quizzes->pluck('id');
        $quizAttempts = QuizAttempt::query()
            ->where('user_id', $user->id)
            ->whereIn('quiz_id', $quizIds)
            ->where('status', 'submitted')
            ->latest('submitted_at')
            ->get()
            ->groupBy('quiz_id')
            ->map(fn ($attempts) => $attempts->first());

        $upcomingEvents = CalendarEvent::query()
            ->where('course_id', $course->id)
            ->where('starts_at', '>=', now()->subHour())
            ->orderBy('starts_at')
            ->limit(5)
            ->get();

        $assignments = Assignment::query()
            ->where('course_id', $course->id)
            ->where('status', 'published')
            ->orderByRaw('due_at is null')
            ->orderBy('due_at')
            ->limit(8)
            ->get();

        return view('student.courses.show', compact(
            'course',
            'completedLessonIds',
            'completedCount',
            'lessonsCount',
            'progressPercent',
            'continueLesson',
            'quizAttempts',
            'upcomingEvents',
            'assignments',
        ));
    }
}
