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

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();
        $courses = Course::query()
            ->where('instructor_user_id', $user->id)
            ->withCount(['lessons', 'enrollments', 'quizzes'])
            ->orderBy('title')
            ->get();

        $courseIds = $courses->pluck('id');

        $studentsCount = $courseIds->isEmpty()
            ? 0
            : Enrollment::query()
                ->whereIn('course_id', $courseIds)
                ->where('status', 'active')
                ->distinct('user_id')
                ->count('user_id');

        $lessonsCount = $courseIds->isEmpty() ? 0 : Lesson::query()->whereIn('course_id', $courseIds)->count();
        $quizzesCount = $courseIds->isEmpty() ? 0 : Quiz::query()->whereIn('course_id', $courseIds)->count();
        $assignmentsCount = $courseIds->isEmpty() ? 0 : Assignment::query()->whereIn('course_id', $courseIds)->count();
        $sessionsCount = $courseIds->isEmpty() ? 0 : LiveSession::query()->whereIn('course_id', $courseIds)->count();
        $announcementsCount = $courseIds->isEmpty() ? 0 : Announcement::query()->whereIn('course_id', $courseIds)->count();

        $pendingSubmissions = $courseIds->isEmpty()
            ? 0
            : AssignmentSubmission::query()
                ->whereHas('assignment', fn ($q) => $q->whereIn('course_id', $courseIds))
                ->whereNull('score')
                ->count();

        $byStatus = [
            'published' => $courses->where('status', 'published')->count(),
            'draft' => $courses->where('status', 'draft')->count(),
            'archived' => $courses->where('status', 'archived')->count(),
        ];

        return view('instructor.dashboard', [
            'courses' => $courses,
            'stats' => [
                'courses' => $courses->count(),
                'students' => $studentsCount,
                'lessons' => $lessonsCount,
                'quizzes' => $quizzesCount,
                'assignments' => $assignmentsCount,
                'sessions' => $sessionsCount,
                'announcements' => $announcementsCount,
                'pending_submissions' => $pendingSubmissions,
            ],
            'byStatus' => $byStatus,
        ]);
    }
}
