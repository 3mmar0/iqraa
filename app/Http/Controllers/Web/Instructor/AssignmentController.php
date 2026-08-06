<?php

namespace App\Http\Controllers\Web\Instructor;

use App\Http\Controllers\Controller;
use App\Models\Assignment;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AssignmentController extends Controller
{
    public function index(Request $request): View
    {
        $courseIds = Course::query()
            ->where('instructor_user_id', $request->user()->id)
            ->pluck('id');

        $assignments = $courseIds->isEmpty()
            ? collect()
            : Assignment::query()
                ->with(['course:id,title', 'lesson:id,title'])
                ->withCount('submissions')
                ->whereIn('course_id', $courseIds)
                ->latest()
                ->get();

        $courses = Course::query()
            ->where('instructor_user_id', $request->user()->id)
            ->orderBy('title')
            ->get(['id', 'title']);

        return view('instructor.assignments.index', compact('assignments', 'courses'));
    }
}
