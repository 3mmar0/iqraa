<?php

namespace App\Http\Controllers\Web\Public;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        $courses = Course::query()
            ->with('instructor')
            ->withCount('lessons')
            ->where('status', 'published')
            ->orderBy('title')
            ->limit(6)
            ->get();

        $courseCount = Course::query()->where('status', 'published')->count();

        return view('public.home', compact('courses', 'courseCount'));
    }
}
