<?php

namespace App\Http\Controllers\Web\Instructor;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Modules\Teaching\Services\CourseAuthoringService;

class CourseController extends Controller
{
    public function __construct(private CourseAuthoringService $authoring)
    {
    }

    public function index(Request $request): View
    {
        $courses = Course::query()
            ->where('instructor_user_id', $request->user()->id)
            ->withCount(['lessons', 'enrollments'])
            ->latest()
            ->get();

        return view('instructor.courses.index', compact('courses'));
    }

    public function create(): View
    {
        return view('instructor.courses.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'hours' => ['nullable', 'numeric', 'min:0'],
            'schedule_text' => ['nullable', 'string', 'max:500'],
            'term_label' => ['nullable', 'string', 'max:100'],
        ]);

        $course = $this->authoring->createCourse($request->user(), $validated);

        return redirect()
            ->route('instructor.courses.show', $course)
            ->with('status', 'تم إنشاء المقرر.');
    }

    public function show(Request $request, Course $course): View
    {
        $this->authorize('view', $course);

        $course->load(['lessons.mediaAssets', 'quizzes.questions', 'enrollments']);

        return view('instructor.courses.show', compact('course'));
    }
}