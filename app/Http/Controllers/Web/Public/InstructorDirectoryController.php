<?php

namespace App\Http\Controllers\Web\Public;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\View\View;

class InstructorDirectoryController extends Controller
{
    public function index(): View
    {
        $instructors = User::query()
            ->whereHas('roles', fn ($q) => $q->where('slug', 'instructor'))
            ->where('status', 'active')
            ->withCount([
                'instructedCourses as published_courses_count' => fn ($q) => $q->where('status', 'published'),
            ])
            ->orderBy('name')
            ->get();

        return view('public.pages.instructors', compact('instructors'));
    }
}
