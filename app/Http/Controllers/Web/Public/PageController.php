<?php

namespace App\Http\Controllers\Web\Public;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\User;
use Illuminate\View\View;

class PageController extends Controller
{
    public function about(): View
    {
        $stats = [
            'courses' => Course::query()->where('status', 'published')->count(),
            'instructors' => User::query()->whereHas('roles', fn ($q) => $q->where('slug', 'instructor'))->count(),
            'students' => User::query()->whereHas('roles', fn ($q) => $q->where('slug', 'student'))->count(),
        ];

        return view('public.pages.about', compact('stats'));
    }

    public function howItWorks(): View
    {
        return view('public.pages.how-it-works');
    }

    public function privacy(): View
    {
        return view('public.pages.privacy');
    }

    public function terms(): View
    {
        return view('public.pages.terms');
    }
}
