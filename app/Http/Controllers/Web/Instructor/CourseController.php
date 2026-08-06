<?php

namespace App\Http\Controllers\Web\Instructor;

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
use Modules\Students\Services\StudentAdminService;

class CourseController extends Controller
{
    public function __construct(
        private readonly CourseAdminService $courses,
        private readonly StudentAdminService $students,
    ) {
    }

    public function index(Request $request): View
    {
        $query = Course::query()
            ->where('instructor_user_id', $request->user()->id)
            ->with(['category', 'academicYear', 'semester'])
            ->withCount(['lessons', 'enrollments', 'quizzes'])
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

        $courses = $query->paginate(20)->withQueryString();
        $categories = Category::query()->where('status', 'active')->orderBy('name')->get(['id', 'name']);
        $coursePanel = 'instructor';

        return view('instructor.courses.index', compact('courses', 'categories', 'coursePanel'));
    }

    public function create(): View
    {
        return view('instructor.courses.create', array_merge($this->formData(), [
            'coursePanel' => 'instructor',
            'lockInstructor' => true,
        ]));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate($this->rules(), [
            'required' => 'هذا الحقل مطلوب.',
        ]);

        $validated['instructor_user_id'] = $request->user()->id;

        $course = Course::query()->create($validated);

        if (class_exists(AuditLogger::class)) {
            app(AuditLogger::class)->log($request->user(), 'course.created', 'course', $course->id);
        }

        return redirect()
            ->route('instructor.courses.show', $course)
            ->with('status', 'تم إنشاء المقرر.');
    }

    public function show(Request $request, Course $course): View
    {
        $this->authorize('view', $course);

        $tab = $request->query('tab', 'general');
        $allowedTabs = ['general', 'lessons', 'files', 'videos', 'quizzes', 'assignments', 'students', 'analytics', 'reviews', 'settings'];

        if (! in_array($tab, $allowedTabs, true)) {
            $tab = 'general';
        }

        $relations = [
            'instructor',
            'category',
            'academicYear',
            'semester',
            'lessons' => fn ($q) => $q->orderBy('position')->with('mediaAssets'),
            'enrollments.user',
        ];

        if ($tab === 'quizzes') {
            $relations[] = 'quizzes.questions.options';
        } else {
            $relations[] = 'quizzes';
        }

        if ($tab === 'assignments') {
            $relations['assignments'] = fn ($q) => $q->with([
                'lesson',
                'submissions' => fn ($sq) => $sq->with('user')->latest('submitted_at')->limit(50),
            ]);
        } else {
            $relations[] = 'assignments.lesson';
        }

        $course->load($relations);
        $course->loadCount(['lessons', 'enrollments']);

        $availableStudents = collect();
        if ($tab === 'students') {
            $enrolledIds = $course->enrollments->where('status', 'active')->pluck('user_id');
            $availableStudents = User::query()
                ->whereHas('roles', fn ($q) => $q->where('slug', 'student'))
                ->whereNotIn('id', $enrolledIds)
                ->where('status', 'active')
                ->orderBy('name')
                ->limit(300)
                ->get(['id', 'name', 'email']);
        }

        $coursePanel = 'instructor';

        return view('admin.courses.show', compact('course', 'tab', 'availableStudents', 'coursePanel'));
    }

    public function edit(Course $course): View
    {
        $this->authorize('update', $course);

        return view('instructor.courses.edit', array_merge(['course' => $course], $this->formData(), [
            'coursePanel' => 'instructor',
            'lockInstructor' => true,
        ]));
    }

    public function update(Request $request, Course $course): RedirectResponse
    {
        $this->authorize('update', $course);

        $validated = $request->validate($this->rules(), [
            'required' => 'هذا الحقل مطلوب.',
        ]);

        $validated['instructor_user_id'] = $request->user()->id;
        $course->update($validated);

        if (class_exists(AuditLogger::class)) {
            app(AuditLogger::class)->log($request->user(), 'course.updated', 'course', $course->id);
        }

        return redirect()
            ->route('instructor.courses.show', $course)
            ->with('status', 'تم تحديث المقرر.');
    }

    public function destroy(Request $request, Course $course): RedirectResponse
    {
        $this->authorize('update', $course);

        $id = $course->id;
        $course->delete();

        if (class_exists(AuditLogger::class)) {
            app(AuditLogger::class)->log($request->user(), 'course.deleted', 'course', $id);
        }

        return redirect()->route('instructor.courses.index')->with('status', 'تم حذف المقرر.');
    }

    public function archive(Request $request, Course $course): RedirectResponse
    {
        $this->authorize('update', $course);
        $this->courses->archive($course, $request->user());

        return back()->with('status', 'تم أرشفة المقرر.');
    }

    public function duplicate(Request $request, Course $course): RedirectResponse
    {
        $this->authorize('update', $course);
        $copy = $this->courses->duplicate($course, $request->user());
        $copy->update(['instructor_user_id' => $request->user()->id]);

        return redirect()->route('instructor.courses.show', $copy)->with('status', 'تم نسخ المقرر.');
    }

    public function publish(Request $request, Course $course): RedirectResponse
    {
        $this->authorize('update', $course);
        $this->courses->publish($course, $request->user());

        return back()->with('status', 'تم نشر المقرر.');
    }

    public function hide(Request $request, Course $course): RedirectResponse
    {
        $this->authorize('update', $course);
        $this->courses->hide($course, $request->user());

        return back()->with('status', 'تم إخفاء المقرر.');
    }

    public function assignSemester(Request $request, Course $course): RedirectResponse
    {
        $this->authorize('update', $course);

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

    public function enrollStudent(Request $request, Course $course): RedirectResponse
    {
        $this->authorize('update', $course);

        $validated = $request->validate([
            'user_id' => ['required', 'integer', 'exists:users,id'],
        ], [
            'user_id.required' => 'اختر طالباً.',
        ]);

        $student = User::query()->findOrFail($validated['user_id']);
        abort_unless($student->hasRole('student'), 422);

        $this->students->assignCourse($student, $course->id, $request->user());

        return redirect()
            ->route('instructor.courses.show', ['course' => $course, 'tab' => 'students'])
            ->with('status', 'تم إلحاق الطالب بالمقرر.');
    }

    public function unenrollStudent(Request $request, Course $course): RedirectResponse
    {
        $this->authorize('update', $course);

        $validated = $request->validate([
            'user_id' => ['required', 'integer', 'exists:users,id'],
        ]);

        $student = User::query()->findOrFail($validated['user_id']);
        $this->students->removeCourse($student, $course->id, $request->user());

        return redirect()
            ->route('instructor.courses.show', ['course' => $course, 'tab' => 'students'])
            ->with('status', 'تم إزالة الطالب من المقرر.');
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
