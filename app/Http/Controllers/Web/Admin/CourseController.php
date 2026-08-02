<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Models\AcademicYear;
use App\Models\Category;
use App\Models\Course;
use App\Models\Semester;
use App\Models\User;
use App\Services\AuditLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Modules\Catalog\Services\CourseAdminService;

class CourseController extends Controller
{
    public function __construct(private readonly CourseAdminService $courses)
    {
    }

    public function index(Request $request): View
    {
        $query = Course::query()
            ->with(['instructor', 'category', 'academicYear', 'semester'])
            ->withCount(['lessons', 'enrollments'])
            ->latest();

        if ($search = trim((string) $request->query('q', ''))) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($status = $request->query('status')) {
            $query->where('status', $status);
        }

        if ($categoryId = $request->query('category_id')) {
            $query->where('category_id', $categoryId);
        }

        if ($yearId = $request->query('academic_year_id')) {
            $query->where('academic_year_id', $yearId);
        }

        $courses = $query->paginate(20)->withQueryString();
        $categories = Category::query()->where('status', 'active')->orderBy('name')->get(['id', 'name']);

        return view('admin.courses.index', compact('courses', 'categories'));
    }

    public function create(): View
    {
        return view('admin.courses.create', $this->formData());
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate($this->rules(), [
            'required' => 'هذا الحقل مطلوب.',
        ]);

        $course = Course::query()->create($validated);

        if (class_exists(AuditLogger::class)) {
            app(AuditLogger::class)->log($request->user(), 'course.created', 'course', $course->id);
        }

        return redirect()->route('admin.courses.show', $course)->with('status', 'تم إنشاء المقرر.');
    }

    public function show(Request $request, Course $course): View
    {
        $tab = $request->query('tab', 'general');
        $allowedTabs = ['general', 'lessons', 'files', 'videos', 'quizzes', 'assignments', 'students', 'analytics', 'reviews', 'settings'];

        if (! in_array($tab, $allowedTabs, true)) {
            $tab = 'general';
        }

        $course->load([
            'instructor',
            'category',
            'academicYear',
            'semester',
            'lessons' => fn ($q) => $q->orderBy('position')->with('mediaAssets'),
            'quizzes',
            'assignments',
            'enrollments.user',
        ]);

        $course->loadCount(['lessons', 'enrollments']);

        return view('admin.courses.show', compact('course', 'tab'));
    }

    public function edit(Course $course): View
    {
        return view('admin.courses.edit', array_merge(['course' => $course], $this->formData()));
    }

    public function update(Request $request, Course $course): RedirectResponse
    {
        $validated = $request->validate($this->rules($course), [
            'required' => 'هذا الحقل مطلوب.',
        ]);

        $course->update($validated);

        if (class_exists(AuditLogger::class)) {
            app(AuditLogger::class)->log($request->user(), 'course.updated', 'course', $course->id);
        }

        return redirect()->route('admin.courses.show', $course)->with('status', 'تم تحديث المقرر.');
    }

    public function destroy(Request $request, Course $course): RedirectResponse
    {
        $id = $course->id;
        $course->delete();

        if (class_exists(AuditLogger::class)) {
            app(AuditLogger::class)->log($request->user(), 'course.deleted', 'course', $id);
        }

        return redirect()->route('admin.courses.index')->with('status', 'تم حذف المقرر.');
    }

    public function archive(Request $request, Course $course): RedirectResponse
    {
        $this->courses->archive($course, $request->user());

        return back()->with('status', 'تم أرشفة المقرر.');
    }

    public function duplicate(Request $request, Course $course): RedirectResponse
    {
        $copy = $this->courses->duplicate($course, $request->user());

        return redirect()->route('admin.courses.show', $copy)->with('status', 'تم نسخ المقرر.');
    }

    public function publish(Request $request, Course $course): RedirectResponse
    {
        $this->courses->publish($course, $request->user());

        return back()->with('status', 'تم نشر المقرر.');
    }

    public function hide(Request $request, Course $course): RedirectResponse
    {
        $this->courses->hide($course, $request->user());

        return back()->with('status', 'تم إخفاء المقرر.');
    }

    public function assignTeacher(Request $request, Course $course): RedirectResponse
    {
        $validated = $request->validate([
            'instructor_user_id' => ['required', 'integer', 'exists:users,id'],
        ], [
            'required' => 'هذا الحقل مطلوب.',
        ]);

        $this->courses->assignTeacher($course, (int) $validated['instructor_user_id'], $request->user());

        return back()->with('status', 'تم تعيين المحاضر.');
    }

    public function assignSemester(Request $request, Course $course): RedirectResponse
    {
        $validated = $request->validate([
            'academic_year_id' => ['nullable', 'integer', 'exists:academic_years,id'],
            'semester_id' => ['nullable', 'integer', 'exists:semesters,id'],
        ]);

        $this->courses->assignSemester(
            $course,
            isset($validated['academic_year_id']) ? (int) $validated['academic_year_id'] : null,
            isset($validated['semester_id']) ? (int) $validated['semester_id'] : null,
            $request->user()
        );

        return back()->with('status', 'تم تعيين السنة والفصل الدراسي.');
    }

    /** @return array<string, mixed> */
    private function formData(): array
    {
        return [
            'instructors' => User::query()
                ->whereHas('roles', fn ($q) => $q->whereIn('slug', ['instructor', 'super_admin']))
                ->orderBy('name')
                ->get(),
            'categories' => Category::query()->where('status', 'active')->orderBy('name')->get(['id', 'name']),
            'academicYears' => AcademicYear::query()->orderByDesc('starts_on')->get(['id', 'name']),
            'semesters' => Semester::query()->with('academicYear')->orderByDesc('starts_on')->get(['id', 'name', 'academic_year_id']),
        ];
    }

    /** @return array<string, list<mixed>> */
    private function rules(?Course $course = null): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'instructor_user_id' => ['required', 'integer', 'exists:users,id'],
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
