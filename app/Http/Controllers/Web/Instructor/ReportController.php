<?php

namespace App\Http\Controllers\Web\Instructor;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Lesson;
use App\Models\Quiz;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReportController extends Controller
{
    public function index(Request $request): View
    {
        $userId = $request->user()->id;
        $courseIds = Course::query()->where('instructor_user_id', $userId)->pluck('id');

        $stats = [
            'courses' => $courseIds->count(),
            'lessons' => Lesson::query()->whereIn('course_id', $courseIds)->count(),
            'quizzes' => Quiz::query()->whereIn('course_id', $courseIds)->count(),
            'students' => Enrollment::query()
                ->whereIn('course_id', $courseIds)
                ->where('status', 'active')
                ->distinct('user_id')
                ->count('user_id'),
        ];

        return view('instructor.reports.index', compact('stats'));
    }
}