<?php

namespace App\Http\Controllers\Web\Public;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\View\View;

class CourseCatalogController extends Controller
{
    public function index(): View
    {
        $courses = Course::query()
            ->with('instructor')
            ->where('status', 'published')
            ->orderBy('title')
            ->get();

        return view('public.courses.index', compact('courses'));
    }

    public function show(Course $course): View
    {
        abort_unless($course->status === 'published', 404);

        $course->load(['instructor', 'lessons']);

        return view('public.courses.show', compact('course'));
    }
}