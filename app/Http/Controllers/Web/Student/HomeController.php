<?php

namespace App\Http\Controllers\Web\Student;

use App\Http\Controllers\Controller;
use App\Models\CourseAccessRequest;
use App\Models\Enrollment;
use App\Models\LessonProgress;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
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

        $lastProgress = LessonProgress::query()
            ->with('lesson.course')
            ->where('user_id', $user->id)
            ->latest('updated_at')
            ->first();

        $pendingRequests = CourseAccessRequest::query()
            ->where('user_id', $user->id)
            ->where('status', 'pending')
            ->count();

        $overallPercent = 0;
        if ($courses->isNotEmpty()) {
            $overallPercent = (int) round($courses->avg('progress_percent'));
        }

        return view('student.home', [
            'user' => $user,
            'courses' => $courses,
            'lastProgress' => $lastProgress,
            'pendingRequests' => $pendingRequests,
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
