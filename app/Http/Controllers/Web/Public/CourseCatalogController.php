<?php

namespace App\Http\Controllers\Web\Public;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CourseCatalogController extends Controller
{
    public function index(Request $request): View
    {
        $query = Course::query()
            ->with(['instructor', 'category'])
            ->withCount(['lessons', 'enrollments'])
            ->where('status', 'published');

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->integer('category_id'));
        }

        if ($request->filled('q')) {
            $search = $request->string('q')->trim();
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $sort = $request->string('sort', 'newest')->toString();
        match ($sort) {
            'title_asc' => $query->orderBy('title'),
            'title_desc' => $query->orderByDesc('title'),
            default => $query->orderByDesc('created_at'),
        };

        $courses = $query->get();

        $categories = Category::query()
            ->where('status', 'active')
            ->withCount(['courses' => fn ($q) => $q->where('status', 'published')])
            ->orderBy('position')
            ->orderBy('name')
            ->get();

        $activeCategoryId = $request->integer('category_id') ?: null;
        $searchQuery = $request->string('q')->trim()->toString();

        return view('public.courses.index', compact(
            'courses',
            'categories',
            'activeCategoryId',
            'searchQuery',
            'sort',
        ));
    }

    public function show(Course $course): View
    {
        abort_unless($course->status === 'published', 404);

        $course->load([
            'instructor',
            'category',
            'lessons' => fn ($q) => $q->where('status', 'published')->orderBy('position'),
        ]);
        $course->loadCount('enrollments');

        return view('public.courses.show', compact('course'));
    }
}
