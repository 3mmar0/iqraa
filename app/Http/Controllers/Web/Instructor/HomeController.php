<?php

namespace App\Http\Controllers\Web\Instructor;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use App\Models\Assignment;
use App\Models\AssignmentSubmission;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Lesson;
use App\Models\LiveSession;
use App\Models\Quiz;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();

        $courses = Course::query()
            ->where('instructor_user_id', $user->id)
            ->withCount(['lessons', 'enrollments', 'quizzes', 'assignments'])
            ->latest()
            ->get();

        $courseIds = $courses->pluck('id');

        $studentsCount = $courseIds->isEmpty()
            ? 0
            : Enrollment::query()
                ->whereIn('course_id', $courseIds)
                ->where('status', 'active')
                ->distinct('user_id')
                ->count('user_id');

        $lessonsCount = $courseIds->isEmpty()
            ? 0
            : Lesson::query()->whereIn('course_id', $courseIds)->count();

        $quizzesCount = $courseIds->isEmpty()
            ? 0
            : Quiz::query()->whereIn('course_id', $courseIds)->count();

        $pendingSubmissions = $courseIds->isEmpty()
            ? 0
            : AssignmentSubmission::query()
                ->whereHas('assignment', fn ($q) => $q->whereIn('course_id', $courseIds))
                ->where(function ($q) {
                    $q->whereNull('score')->orWhere('status', 'submitted');
                })
                ->count();

        $upcomingSessions = $courseIds->isEmpty()
            ? collect()
            : LiveSession::query()
                ->with('course:id,title')
                ->whereIn('course_id', $courseIds)
                ->where('starts_at', '>=', now()->subHour())
                ->orderBy('starts_at')
                ->limit(5)
                ->get();

        $recentAnnouncements = $courseIds->isEmpty()
            ? collect()
            : Announcement::query()
                ->with('course:id,title')
                ->whereIn('course_id', $courseIds)
                ->latest('published_at')
                ->limit(4)
                ->get();

        $recentEnrollments = $courseIds->isEmpty()
            ? collect()
            : Enrollment::query()
                ->with(['user:id,name,email', 'course:id,title'])
                ->whereIn('course_id', $courseIds)
                ->where('status', 'active')
                ->latest()
                ->limit(6)
                ->get();

        $dueSoonAssignments = $courseIds->isEmpty()
            ? collect()
            : Assignment::query()
                ->with('course:id,title')
                ->withCount('submissions')
                ->whereIn('course_id', $courseIds)
                ->whereNotNull('due_at')
                ->where('due_at', '>=', now())
                ->orderBy('due_at')
                ->limit(5)
                ->get();

        return view('instructor.home', [
            'user' => $user,
            'courses' => $courses,
            'stats' => [
                'courses' => $courses->count(),
                'published' => $courses->where('status', 'published')->count(),
                'students' => $studentsCount,
                'lessons' => $lessonsCount,
                'quizzes' => $quizzesCount,
                'pending_submissions' => $pendingSubmissions,
            ],
            'upcomingSessions' => $upcomingSessions,
            'recentAnnouncements' => $recentAnnouncements,
            'recentEnrollments' => $recentEnrollments,
            'dueSoonAssignments' => $dueSoonAssignments,
        ]);
    }
}
