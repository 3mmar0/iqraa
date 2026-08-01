<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Lesson;
use App\Services\AuditLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class LessonController extends Controller
{
    public function index(Request $request): View
    {
        $query = Lesson::query()->with('course')->latest();

        if ($search = trim((string) $request->query('q', ''))) {
            $query->where('title', 'like', "%{$search}%");
        }

        if ($courseId = $request->query('course_id')) {
            $query->where('course_id', $courseId);
        }

        if ($status = $request->query('status')) {
            $query->where('status', $status);
        }

        $lessons = $query->paginate(20)->withQueryString();
        $courses = Course::query()->orderBy('title')->get(['id', 'title']);

        return view('admin.lessons.index', compact('lessons', 'courses'));
    }

    public function create(Request $request): View
    {
        $courses = Course::query()->orderBy('title')->get(['id', 'title']);
        $selectedCourseId = $request->query('course_id');

        return view('admin.lessons.create', compact('courses', 'selectedCourseId'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'course_id' => ['required', 'integer', 'exists:courses,id'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'position' => ['nullable', 'integer', 'min:1'],
            'status' => ['required', 'string', Rule::in(['draft', 'published', 'archived'])],
        ], [
            'required' => 'هذا الحقل مطلوب.',
        ]);

        $course = Course::query()->findOrFail($validated['course_id']);
        $validated['position'] = $validated['position'] ?? ((int) $course->lessons()->max('position') + 1);

        $lesson = Lesson::query()->create($validated);

        if (class_exists(AuditLogger::class)) {
            app(AuditLogger::class)->log($request->user(), 'lesson.created', 'lesson', $lesson->id);
        }

        return redirect()->route('admin.lessons.index', ['course_id' => $course->id])
            ->with('status', 'تم إنشاء الدرس.');
    }

    public function edit(Lesson $lesson): View
    {
        $courses = Course::query()->orderBy('title')->get(['id', 'title']);

        return view('admin.lessons.edit', compact('lesson', 'courses'));
    }

    public function update(Request $request, Lesson $lesson): RedirectResponse
    {
        $validated = $request->validate([
            'course_id' => ['required', 'integer', 'exists:courses,id'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'position' => ['nullable', 'integer', 'min:1'],
            'status' => ['required', 'string', Rule::in(['draft', 'published', 'archived'])],
        ], [
            'required' => 'هذا الحقل مطلوب.',
        ]);

        $lesson->update($validated);

        if (class_exists(AuditLogger::class)) {
            app(AuditLogger::class)->log($request->user(), 'lesson.updated', 'lesson', $lesson->id);
        }

        return redirect()->route('admin.lessons.index', ['course_id' => $lesson->course_id])
            ->with('status', 'تم تحديث الدرس.');
    }

    public function destroy(Request $request, Lesson $lesson): RedirectResponse
    {
        $courseId = $lesson->course_id;
        $id = $lesson->id;
        $lesson->delete();

        if (class_exists(AuditLogger::class)) {
            app(AuditLogger::class)->log($request->user(), 'lesson.deleted', 'lesson', $id);
        }

        return redirect()->route('admin.lessons.index', ['course_id' => $courseId])
            ->with('status', 'تم حذف الدرس.');
    }
}
