<?php

namespace App\Http\Controllers\Web\Instructor;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Modules\Teaching\Services\CourseAuthoringService;

class LessonController extends Controller
{
    public function __construct(private CourseAuthoringService $authoring)
    {
    }

    public function store(Request $request, Course $course): RedirectResponse
    {
        $this->authorize('update', $course);

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'position' => ['nullable', 'integer', 'min:1'],
        ]);

        $this->authoring->createLesson($course, $validated);

        return back()->with('status', 'تم إضافة الدرس.');
    }
}