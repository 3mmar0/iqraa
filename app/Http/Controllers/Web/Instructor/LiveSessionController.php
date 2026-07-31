<?php

namespace App\Http\Controllers\Web\Instructor;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\LiveSession;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LiveSessionController extends Controller
{
    public function index(Request $request): View
    {
        $courseIds = Course::query()
            ->where('instructor_user_id', $request->user()->id)
            ->pluck('id');

        $sessions = LiveSession::query()
            ->with('course')
            ->whereIn('course_id', $courseIds)
            ->orderBy('starts_at')
            ->get();

        $courses = Course::query()
            ->where('instructor_user_id', $request->user()->id)
            ->orderBy('title')
            ->get();

        return view('instructor.live-sessions.index', compact('sessions', 'courses'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'course_id' => ['required', 'exists:courses,id'],
            'title' => ['required', 'string', 'max:255'],
            'starts_at' => ['required', 'date'],
            'join_url' => ['nullable', 'url', 'max:500'],
        ]);

        $course = Course::query()->findOrFail($validated['course_id']);
        $this->authorize('update', $course);

        LiveSession::query()->create([
            'course_id' => $course->id,
            'title' => $validated['title'],
            'starts_at' => $validated['starts_at'],
            'join_url' => $validated['join_url'] ?? null,
            'status' => 'scheduled',
        ]);

        return back()->with('status', 'تم جدولة الجلسة المباشرة.');
    }
}