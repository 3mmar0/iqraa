<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\User;
use App\Services\AuditLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class CourseController extends Controller
{
    public function index(Request $request): View
    {
        $query = Course::query()->with('instructor')->withCount(['lessons', 'enrollments'])->latest();

        if ($search = trim((string) $request->query('q', ''))) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($status = $request->query('status')) {
            $query->where('status', $status);
        }

        $courses = $query->paginate(20)->withQueryString();

        return view('admin.courses.index', compact('courses'));
    }

    public function create(): View
    {
        $instructors = User::query()
            ->whereHas('roles', fn ($q) => $q->whereIn('slug', ['instructor', 'super_admin']))
            ->orderBy('name')
            ->get();

        return view('admin.courses.create', compact('instructors'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'instructor_user_id' => ['required', 'integer', 'exists:users,id'],
            'hours' => ['nullable', 'numeric', 'min:0'],
            'status' => ['required', 'string', Rule::in(['draft', 'published', 'archived'])],
            'schedule_text' => ['nullable', 'string', 'max:500'],
            'term_label' => ['nullable', 'string', 'max:100'],
        ], [
            'required' => 'هذا الحقل مطلوب.',
        ]);

        $course = Course::query()->create($validated);

        if (class_exists(AuditLogger::class)) {
            app(AuditLogger::class)->log($request->user(), 'course.created', 'course', $course->id);
        }

        return redirect()->route('admin.courses.show', $course)->with('status', 'تم إنشاء المقرر.');
    }

    public function show(Course $course): View
    {
        $course->load(['instructor', 'lessons', 'enrollments.user']);

        return view('admin.courses.show', compact('course'));
    }

    public function edit(Course $course): View
    {
        $instructors = User::query()
            ->whereHas('roles', fn ($q) => $q->whereIn('slug', ['instructor', 'super_admin']))
            ->orderBy('name')
            ->get();

        return view('admin.courses.edit', compact('course', 'instructors'));
    }

    public function update(Request $request, Course $course): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'instructor_user_id' => ['required', 'integer', 'exists:users,id'],
            'hours' => ['nullable', 'numeric', 'min:0'],
            'status' => ['required', 'string', Rule::in(['draft', 'published', 'archived'])],
            'schedule_text' => ['nullable', 'string', 'max:500'],
            'term_label' => ['nullable', 'string', 'max:100'],
        ], [
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
}
