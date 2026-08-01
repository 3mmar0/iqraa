<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\Quiz;
use App\Services\AuditLogger;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Modules\Catalog\Services\LessonAdminService;

class LessonController extends Controller
{
    public function __construct(private readonly LessonAdminService $lessons)
    {
    }

    public function index(Request $request): View
    {
        $query = Lesson::query()->with(['course', 'quiz'])->latest();

        if ($search = trim((string) $request->query('q', ''))) {
            $query->where('title', 'like', "%{$search}%");
        }

        if ($courseId = $request->query('course_id')) {
            $query->where('course_id', $courseId);
        }

        if ($status = $request->query('status')) {
            $query->where('status', $status);
        }

        if ($request->query('locked') === '1') {
            $query->where('is_locked', true);
        }

        $lessons = $query->paginate(20)->withQueryString();
        $courses = Course::query()->orderBy('title')->get(['id', 'title']);

        return view('admin.lessons.index', compact('lessons', 'courses'));
    }

    public function create(Request $request): View
    {
        $courses = Course::query()->orderBy('title')->get(['id', 'title']);
        $quizzes = Quiz::query()->orderBy('title')->get(['id', 'title', 'course_id']);
        $selectedCourseId = $request->query('course_id');

        return view('admin.lessons.create', compact('courses', 'quizzes', 'selectedCourseId'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate($this->rules(), [
            'required' => 'هذا الحقل مطلوب.',
        ]);

        $course = Course::query()->findOrFail($validated['course_id']);
        $validated['position'] = $validated['position'] ?? ((int) $course->lessons()->max('position') + 1);
        $validated['is_locked'] = $request->boolean('is_locked');
        $validated['published_at'] = $validated['published_at'] ?? null;

        $lesson = Lesson::query()->create($validated);

        if (class_exists(AuditLogger::class)) {
            app(AuditLogger::class)->log($request->user(), 'lesson.created', 'lesson', $lesson->id);
        }

        return redirect()->route('admin.lessons.show', $lesson)->with('status', 'تم إنشاء الدرس.');
    }

    public function show(Request $request, Lesson $lesson): View
    {
        $section = $request->query('section', 'general');
        $allowed = ['general', 'video', 'files', 'resources', 'quiz', 'notes', 'comments', 'settings'];

        if (! in_array($section, $allowed, true)) {
            $section = 'general';
        }

        $lesson->load(['course', 'quiz.questions', 'mediaAssets']);

        return view('admin.lessons.show', compact('lesson', 'section'));
    }

    public function edit(Lesson $lesson): View
    {
        $courses = Course::query()->orderBy('title')->get(['id', 'title']);
        $quizzes = Quiz::query()->orderBy('title')->get(['id', 'title', 'course_id']);

        return view('admin.lessons.edit', compact('lesson', 'courses', 'quizzes'));
    }

    public function update(Request $request, Lesson $lesson): RedirectResponse
    {
        $validated = $request->validate($this->rules(), [
            'required' => 'هذا الحقل مطلوب.',
        ]);

        $validated['is_locked'] = $request->boolean('is_locked');
        $validated['published_at'] = $validated['published_at'] ?? null;

        $lesson->update($validated);

        if (class_exists(AuditLogger::class)) {
            app(AuditLogger::class)->log($request->user(), 'lesson.updated', 'lesson', $lesson->id);
        }

        return redirect()->route('admin.lessons.show', $lesson)->with('status', 'تم تحديث الدرس.');
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

    public function lock(Request $request, Lesson $lesson): RedirectResponse
    {
        $this->lessons->lock($lesson, $request->user());

        return back()->with('status', 'تم قفل الدرس.');
    }

    public function unlock(Request $request, Lesson $lesson): RedirectResponse
    {
        $this->lessons->unlock($lesson, $request->user());

        return back()->with('status', 'تم فتح الدرس.');
    }

    public function duplicate(Request $request, Lesson $lesson): RedirectResponse
    {
        $copy = $this->lessons->duplicate($lesson, $request->user());

        return redirect()->route('admin.lessons.show', $copy)->with('status', 'تم نسخ الدرس.');
    }

    public function reorder(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'course_id' => ['required', 'integer', 'exists:courses,id'],
            'positions' => ['required', 'array', 'min:1'],
            'positions.*' => ['integer', 'exists:lessons,id'],
        ], [
            'required' => 'هذا الحقل مطلوب.',
        ]);

        $this->lessons->reorder(
            (int) $validated['course_id'],
            array_map('intval', $validated['positions']),
            $request->user()
        );

        return back()->with('status', 'تم إعادة ترتيب الدروس.');
    }

    public function schedulePublish(Request $request, Lesson $lesson): RedirectResponse
    {
        $validated = $request->validate([
            'published_at' => ['required', 'date'],
        ], [
            'required' => 'هذا الحقل مطلوب.',
        ]);

        $this->lessons->schedulePublish($lesson, Carbon::parse($validated['published_at']), $request->user());

        return back()->with('status', 'تم جدولة نشر الدرس.');
    }

    public function attachQuiz(Request $request, Lesson $lesson): RedirectResponse
    {
        $validated = $request->validate([
            'quiz_id' => ['required', 'integer', 'exists:quizzes,id'],
        ], [
            'required' => 'هذا الحقل مطلوب.',
        ]);

        $quiz = Quiz::query()->findOrFail($validated['quiz_id']);
        $this->lessons->attachQuiz($lesson, $quiz, $request->user());

        return back()->with('status', 'تم ربط الاختبار بالدرس.');
    }

    /** @return array<string, list<mixed>> */
    private function rules(): array
    {
        return [
            'course_id' => ['required', 'integer', 'exists:courses,id'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'position' => ['nullable', 'integer', 'min:1'],
            'status' => ['required', 'string', Rule::in(['draft', 'published', 'archived', 'scheduled'])],
            'is_locked' => ['nullable', 'boolean'],
            'published_at' => ['nullable', 'date'],
            'quiz_id' => ['nullable', 'integer', 'exists:quizzes,id'],
        ];
    }
}
