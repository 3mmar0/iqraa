<?php

namespace App\Http\Controllers\Web\Student;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\LessonProgress;
use Illuminate\Http\Request;
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
            ->get();

        $lastProgress = LessonProgress::query()
            ->with('lesson.course')
            ->where('user_id', $user->id)
            ->latest('updated_at')
            ->first();

        return view('student.home', [
            'user' => $user,
            'enrollments' => $enrollments,
            'lastProgress' => $lastProgress,
            'termLabel' => $enrollments->first()?->course?->term_label ?? 'الترم الحالي',
        ]);
    }
}
