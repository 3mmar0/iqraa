<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Role;
use App\Models\User;
use App\Services\AuditLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;
use Modules\Teaching\Services\TeacherAdminService;

class TeacherController extends Controller
{
    public function __construct(
        private readonly TeacherAdminService $teachers,
        private readonly AuditLogger $audit,
    ) {
    }

    public function index(Request $request): View
    {
        $teachers = $this->teachers->paginate($request->only(['q', 'status']));

        return view('admin.teachers.index', compact('teachers'));
    }

    public function create(): View
    {
        return view('admin.teachers.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'phone' => ['nullable', 'string', 'max:50', 'unique:users,phone'],
            'password' => ['nullable', Password::defaults()],
            'status' => ['required', Rule::in(['invited', 'active', 'disabled'])],
        ], [
            'required' => 'هذا الحقل مطلوب.',
        ]);

        $teacher = User::query()->create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'password' => Hash::make($validated['password'] ?? 'password'),
            'status' => $validated['status'],
            'creation_source' => 'admin_created',
        ]);

        $instructorRole = Role::query()->where('slug', 'instructor')->first();
        if ($instructorRole) {
            $teacher->roles()->syncWithoutDetaching([$instructorRole->id]);
        }

        $this->audit->log($request->user(), 'teacher.created', User::class, $teacher->id);

        return redirect()->route('admin.teachers.show', $teacher)->with('status', 'تم إضافة المعلم.');
    }

    public function show(User $teacher): View
    {
        abort_unless($teacher->hasRole('instructor'), 404);

        $teacher->load(['instructedCourses' => fn ($q) => $q->withCount('enrollments')]);
        $courses = Course::query()->orderBy('title')->get(['id', 'title', 'instructor_user_id']);

        $analytics = [
            'courses_count' => $teacher->instructedCourses->count(),
            'students_count' => $teacher->instructedCourses->sum('enrollments_count'),
            'published_courses' => $teacher->instructedCourses->where('status', 'published')->count(),
        ];

        return view('admin.teachers.show', compact('teacher', 'courses', 'analytics'));
    }

    public function edit(User $teacher): View
    {
        abort_unless($teacher->hasRole('instructor'), 404);

        return view('admin.teachers.edit', compact('teacher'));
    }

    public function update(Request $request, User $teacher): RedirectResponse
    {
        abort_unless($teacher->hasRole('instructor'), 404);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($teacher->id)],
            'phone' => ['nullable', 'string', 'max:50', Rule::unique('users', 'phone')->ignore($teacher->id)],
            'status' => ['required', Rule::in(['invited', 'active', 'disabled'])],
            'password' => ['nullable', Password::defaults()],
        ]);

        if (! empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $teacher->update($validated);

        $this->audit->log($request->user(), 'teacher.updated', User::class, $teacher->id);

        return redirect()->route('admin.teachers.show', $teacher)->with('status', 'تم تحديث بيانات المعلم.');
    }

    public function destroy(Request $request, User $teacher): RedirectResponse
    {
        abort_unless($teacher->hasRole('instructor'), 404);

        if ($teacher->instructedCourses()->exists()) {
            return back()->with('status', 'لا يمكن حذف معلم لديه مقررات — أعد تعيين المقررات أولاً.');
        }

        $id = $teacher->id;
        $teacher->delete();

        $this->audit->log($request->user(), 'teacher.deleted', User::class, $id);

        return redirect()->route('admin.teachers.index')->with('status', 'تم حذف المعلم.');
    }

    public function suspend(Request $request, User $teacher): RedirectResponse
    {
        abort_unless($teacher->hasRole('instructor'), 404);

        $teacher->update(['status' => 'disabled']);

        $this->audit->log($request->user(), 'teacher.suspended', User::class, $teacher->id);

        return back()->with('status', 'تم تعليق حساب المعلم.');
    }

    public function assignCourses(Request $request, User $teacher): RedirectResponse
    {
        abort_unless($teacher->hasRole('instructor'), 404);

        $validated = $request->validate([
            'course_ids' => ['required', 'array', 'min:1'],
            'course_ids.*' => ['integer', 'exists:courses,id'],
        ]);

        $count = $this->teachers->assignCourses($teacher, $validated['course_ids'], $request->user());

        return back()->with('status', "تم تعيين {$count} مقرر للمعلم.");
    }

    public function analytics(User $teacher): View
    {
        return $this->show($teacher);
    }
}
