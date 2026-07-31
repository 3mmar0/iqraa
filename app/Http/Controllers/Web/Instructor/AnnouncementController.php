<?php

namespace App\Http\Controllers\Web\Instructor;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use App\Models\Course;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AnnouncementController extends Controller
{
    public function index(Request $request): View
    {
        $courseIds = Course::query()
            ->where('instructor_user_id', $request->user()->id)
            ->pluck('id');

        $announcements = Announcement::query()
            ->with('course')
            ->whereIn('course_id', $courseIds)
            ->latest()
            ->get();

        $courses = Course::query()
            ->where('instructor_user_id', $request->user()->id)
            ->orderBy('title')
            ->get();

        return view('instructor.announcements.index', compact('announcements', 'courses'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'course_id' => ['required', 'exists:courses,id'],
            'title' => ['required', 'string', 'max:255'],
            'body' => ['required', 'string'],
        ]);

        $course = Course::query()->findOrFail($validated['course_id']);
        $this->authorize('update', $course);

        Announcement::query()->create([
            'title' => $validated['title'],
            'body' => $validated['body'],
            'author_id' => $request->user()->id,
            'course_id' => $course->id,
            'published_at' => now(),
        ]);

        return back()->with('status', 'تم نشر الإعلان.');
    }
}