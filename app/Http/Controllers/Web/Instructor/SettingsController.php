<?php

namespace App\Http\Controllers\Web\Instructor;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Enrollment;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SettingsController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();
        $courseCount = Course::query()->where('instructor_user_id', $user->id)->count();
        $studentCount = Enrollment::query()
            ->whereIn('course_id', Course::query()->where('instructor_user_id', $user->id)->select('id'))
            ->where('status', 'active')
            ->distinct('user_id')
            ->count('user_id');

        return view('instructor.settings.index', compact('user', 'courseCount', 'studentCount'));
    }
}
