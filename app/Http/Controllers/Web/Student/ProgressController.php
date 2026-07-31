<?php

namespace App\Http\Controllers\Web\Student;

use App\Http\Controllers\Controller;
use App\Models\Enrollment;
use App\Models\LessonProgress;
use App\Models\QuizAttempt;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProgressController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();
        $enrollments = Enrollment::query()
            ->with('course.lessons')
            ->where('user_id', $user->id)
            ->where('status', 'active')
            ->get()
            ->map(function (Enrollment $enrollment) use ($user) {
                $lessonIds = $enrollment->course->lessons->pluck('id');
                $completed = LessonProgress::query()
                    ->where('user_id', $user->id)
                    ->whereIn('lesson_id', $lessonIds)
                    ->where('status', 'completed')
                    ->count();
                $quizzes = QuizAttempt::query()
                    ->where('user_id', $user->id)
                    ->where('status', 'submitted')
                    ->whereHas('quiz', fn ($q) => $q->where('course_id', $enrollment->course_id))
                    ->count();

                return [
                    'course' => $enrollment->course,
                    'completed' => $completed,
                    'total' => $lessonIds->count(),
                    'percent' => $lessonIds->count() ? round(($completed / $lessonIds->count()) * 100) : 0,
                    'quizzes' => $quizzes,
                    'hours' => $enrollment->course->hours,
                ];
            });

        return view('student.progress', compact('enrollments'));
    }
}
