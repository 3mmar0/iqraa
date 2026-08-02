<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Models\Assignment;
use App\Models\Course;
use App\Models\Lesson;
use App\Services\AuditLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class AssignmentController extends Controller
{
    use Concerns\ReturnsToCourse;

    public function index(Request $request): View
    {
        $query = Assignment::query()->with(['course', 'lesson'])->withCount('submissions')->latest();

        if ($search = trim((string) $request->query('q', ''))) {
            $query->where('title', 'like', "%{$search}%");
        }

        if ($courseId = $request->query('course_id')) {
            $query->where('course_id', $courseId);
        }

        if ($status = $request->query('status')) {
            $query->where('status', $status);
        }

        $assignments = $query->paginate(20)->withQueryString();
        $courses = Course::query()->orderBy('title')->get(['id', 'title']);

        return view('admin.assignments.index', compact('assignments', 'courses'));
    }

    public function create(): View
    {
        $courses = Course::query()->orderBy('title')->get(['id', 'title']);
        $lessons = Lesson::query()->with('course')->orderBy('title')->get(['id', 'title', 'course_id']);

        return view('admin.assignments.create', compact('courses', 'lessons'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate($this->rules(), [
            'required' => 'هذا الحقل مطلوب.',
        ]);

        $assignment = Assignment::query()->create($validated);

        if (class_exists(AuditLogger::class)) {
            app(AuditLogger::class)->log($request->user(), 'assignment.created', 'assignment', $assignment->id);
        }

        $status = 'تم إنشاء الواجب.';

        return $this->redirectToCourseContext($request, $status, 'assignments')
            ?? redirect()->route('admin.assignments.show', $assignment)->with('status', $status);
    }

    public function show(Assignment $assignment): View
    {
        $assignment->load(['course', 'lesson', 'submissions.user']);

        return view('admin.assignments.show', compact('assignment'));
    }

    public function edit(Assignment $assignment): View
    {
        $courses = Course::query()->orderBy('title')->get(['id', 'title']);
        $lessons = Lesson::query()->with('course')->orderBy('title')->get(['id', 'title', 'course_id']);

        return view('admin.assignments.edit', compact('assignment', 'courses', 'lessons'));
    }

    public function update(Request $request, Assignment $assignment): RedirectResponse
    {
        $validated = $request->validate($this->rules(), [
            'required' => 'هذا الحقل مطلوب.',
        ]);

        $assignment->update($validated);

        if (class_exists(AuditLogger::class)) {
            app(AuditLogger::class)->log($request->user(), 'assignment.updated', 'assignment', $assignment->id);
        }

        $status = 'تم تحديث الواجب.';

        return $this->redirectToCourseContext($request, $status, 'assignments')
            ?? redirect()->route('admin.assignments.show', $assignment)->with('status', $status);
    }

    public function destroy(Request $request, Assignment $assignment): RedirectResponse
    {
        $id = $assignment->id;
        $assignment->delete();

        if (class_exists(AuditLogger::class)) {
            app(AuditLogger::class)->log($request->user(), 'assignment.deleted', 'assignment', $id);
        }

        $status = 'تم حذف الواجب.';

        return $this->redirectToCourseContext($request, $status, 'assignments')
            ?? redirect()->route('admin.assignments.index')->with('status', $status);
    }

    /** @return array<string, list<mixed>> */
    private function rules(): array
    {
        return [
            'course_id' => ['required', 'integer', 'exists:courses,id'],
            'lesson_id' => ['nullable', 'integer', 'exists:lessons,id'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'due_at' => ['required', 'date'],
            'status' => ['required', 'string', Rule::in(['draft', 'published', 'archived'])],
        ];
    }
}
