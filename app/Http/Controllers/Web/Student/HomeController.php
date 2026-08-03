<?php

namespace App\Http\Controllers\Web\Student;

use App\Http\Controllers\Controller;
use App\Models\CalendarEvent;
use App\Models\Course;
use App\Models\CourseAccessRequest;
use App\Models\Enrollment;
use App\Models\LessonProgress;
use App\Models\QuizAttempt;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();

        $enrollments = Enrollment::query()
            ->with(['course.instructor', 'course.lessons'])
            ->where('user_id', $user->id)
            ->where('status', 'active')
            ->latest('updated_at')
            ->get();

        $courses = $this->mapCoursesWithProgress($enrollments, $user->id);
        $courseIds = $enrollments->pluck('course_id');

        $lastProgress = LessonProgress::query()
            ->with('lesson.course')
            ->where('user_id', $user->id)
            ->latest('updated_at')
            ->first();

        $pendingRequests = CourseAccessRequest::query()
            ->with('course')
            ->where('user_id', $user->id)
            ->where('status', 'pending')
            ->latest()
            ->get();

        $upcomingEvents = CalendarEvent::query()
            ->with('course')
            ->where(function ($q) use ($courseIds) {
                $q->whereIn('course_id', $courseIds)->orWhereNull('course_id');
            })
            ->where('starts_at', '>=', now()->subHour())
            ->orderBy('starts_at')
            ->limit(5)
            ->get();

        $notifications = Schema::hasTable('notifications')
            ? $user->notifications()->latest()->limit(5)->get()
            : new Collection;
        $unreadNotifications = Schema::hasTable('notifications')
            ? $user->unreadNotifications()->count()
            : 0;

        $recentAchievements = $user->achievements()
            ->orderByPivot('created_at', 'desc')
            ->limit(3)
            ->get();

        $completedLessons = LessonProgress::query()
            ->where('user_id', $user->id)
            ->where('status', 'completed')
            ->count();

        $submittedQuizzes = QuizAttempt::query()
            ->where('user_id', $user->id)
            ->where('status', 'submitted')
            ->count();

        $enrolledIds = $courseIds->all();
        $discoverCourses = Course::query()
            ->with('instructor')
            ->where('status', 'published')
            ->when($enrolledIds !== [], fn ($q) => $q->whereNotIn('id', $enrolledIds))
            ->latest('updated_at')
            ->limit(3)
            ->get();

        $overallPercent = 0;
        if ($courses->isNotEmpty()) {
            $overallPercent = (int) round($courses->avg('progress_percent'));
        }

        return view('student.home', [
            'user' => $user,
            'courses' => $courses,
            'lastProgress' => $lastProgress,
            'pendingRequests' => $pendingRequests,
            'upcomingEvents' => $upcomingEvents,
            'notifications' => $notifications,
            'unreadNotifications' => $unreadNotifications,
            'recentAchievements' => $recentAchievements,
            'completedLessons' => $completedLessons,
            'submittedQuizzes' => $submittedQuizzes,
            'discoverCourses' => $discoverCourses,
            'overallPercent' => $overallPercent,
            'termLabel' => $courses->first()?->term_label ?? 'الترم الحالي',
        ]);
    }

    /**
     * @param  Collection<int, Enrollment>  $enrollments
     * @return Collection<int, \App\Models\Course>
     */
    private function mapCoursesWithProgress(Collection $enrollments, int $userId): Collection
    {
        return $enrollments->map(function (Enrollment $enrollment) use ($userId) {
            $course = $enrollment->course;
            $lessonIds = $course->lessons->pluck('id');
            $completed = LessonProgress::query()
                ->where('user_id', $userId)
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
    }
}
