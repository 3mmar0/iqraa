<?php

namespace App\Http\Controllers\Web\Instructor;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Enrollment;
use Illuminate\Http\Request;
use Illuminate\View\View;

class StudentRosterController extends Controller
{
    public function index(Request $request): View
    {
        $courseIds = Course::query()
            ->where('instructor_user_id', $request->user()->id)
            ->pluck('id');

        $enrollments = Enrollment::query()
            ->with(['user', 'course'])
            ->whereIn('course_id', $courseIds)
            ->where('status', 'active')
            ->latest()
            ->get();

        return view('instructor.students.index', compact('enrollments'));
    }
}