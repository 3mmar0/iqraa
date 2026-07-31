<?php

namespace App\Http\Controllers\Web\Instructor;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Enrollment;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();
        $courseIds = Course::query()->where('instructor_user_id', $user->id)->pluck('id');

        return view('instructor.dashboard', [
            'title' => 'لوحة المحاضر',
            'coursesCount' => $courseIds->count(),
            'studentsCount' => Enrollment::query()
                ->whereIn('course_id', $courseIds)
                ->where('status', 'active')
                ->distinct('user_id')
                ->count('user_id'),
        ]);
    }
}