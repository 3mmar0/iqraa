<?php

namespace App\Http\Controllers\Web\Instructor;

use App\Http\Controllers\Controller;
use App\Models\AcademicYear;
use App\Models\Category;
use App\Models\Course;
use App\Models\Semester;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
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
            ->withCount(['lessons', 'enrollments', 'quizzes'])
            ->latest()
            ->get();

        return view('instructor.courses.index', compact('courses'));
    }

    public function create(): View
    {
        return view('instructor.courses.create', $this->formData());
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate($this->rules(), [
            'required' => 'هذا الحقل مطلوب.',
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

    /** @return array<string, mixed> */
    private function formData(): array
    {
        return [
            'categories' => Category::query()->where('status', 'active')->orderBy('name')->get(['id', 'name']),
            'academicYears' => AcademicYear::query()->orderByDesc('starts_on')->get(['id', 'name']),
            'semesters' => Semester::query()->with('academicYear')->orderByDesc('starts_on')->get(['id', 'name', 'academic_year_id']),
        ];
    }

    /** @return array<string, list<mixed>> */
    private function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'category_id' => ['nullable', 'integer', 'exists:categories,id'],
            'academic_year_id' => ['nullable', 'integer', 'exists:academic_years,id'],
            'semester_id' => [
                'nullable',
                'integer',
                Rule::exists('semesters', 'id')->where(
                    fn ($query) => $query->where('academic_year_id', request()->input('academic_year_id'))
                ),
            ],
            'hours' => ['nullable', 'numeric', 'min:0'],
            'price' => ['nullable', 'numeric', 'min:0'],
            'status' => ['required', 'string', Rule::in(['draft', 'published', 'archived', 'hidden'])],
        ];
    }
}
