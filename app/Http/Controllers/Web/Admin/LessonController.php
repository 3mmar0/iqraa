<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\MediaAsset;
use App\Models\Quiz;
use App\Services\AuditLogger;
use App\Support\LessonContentSanitizer;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Modules\Catalog\Services\LessonAdminService;

class LessonController extends Controller
{
    use Concerns\ReturnsToCourse;

    public function __construct(
        private readonly LessonAdminService $lessons,
        private readonly LessonContentSanitizer $sanitizer,
    ) {
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
        $validated['content_html'] = $this->sanitizer->sanitize($validated['content_html'] ?? null);
        $validated['main_media_asset_id'] = $this->resolveMainMediaId(null, $validated['main_media_asset_id'] ?? null);

        $lesson = Lesson::query()->create($validated);

        if (class_exists(AuditLogger::class)) {
            app(AuditLogger::class)->log($request->user(), 'lesson.created', 'lesson', $lesson->id);
        }

        $status = 'تم إنشاء الدرس.';

        return $this->redirectToCourseContext($request, $status, 'lessons')
            ?? redirect()->route('admin.lessons.show', $lesson)->with('status', $status);
    }

    public function show(Request $request, Lesson $lesson): View
    {
        $section = $request->query('section', 'general');
        $allowed = ['general', 'video', 'files', 'resources', 'quiz', 'notes', 'comments', 'settings'];

        if (! in_array($section, $allowed, true)) {
            $section = 'general';
        }

        $lesson->load(['course', 'quiz.questions', 'mediaAssets', 'mainMediaAsset']);

        return view('admin.lessons.show', compact('lesson', 'section'));
    }

    public function edit(Lesson $lesson): View
    {
        $courses = Course::query()->orderBy('title')->get(['id', 'title']);
        $quizzes = Quiz::query()->orderBy('title')->get(['id', 'title', 'course_id', 'status']);
        $lesson->load('mediaAssets');

        return view('admin.lessons.edit', compact('lesson', 'courses', 'quizzes'));
    }

    public function update(Request $request, Lesson $lesson): RedirectResponse
    {
        $validated = $request->validate($this->rules($lesson), [
            'required' => 'هذا الحقل مطلوب.',
        ]);

        $validated['is_locked'] = $request->boolean('is_locked');
        $validated['published_at'] = $validated['published_at'] ?? null;
        $validated['content_html'] = $this->sanitizer->sanitize($validated['content_html'] ?? null);
        $validated['main_media_asset_id'] = $this->resolveMainMediaId($lesson, $validated['main_media_asset_id'] ?? null);

        $lesson->update($validated);

        if (class_exists(AuditLogger::class)) {
            app(AuditLogger::class)->log($request->user(), 'lesson.updated', 'lesson', $lesson->id);
        }

        $status = 'تم تحديث الدرس.';

        return $this->redirectToCourseContext($request, $status, 'lessons')
            ?? redirect()->route('admin.lessons.show', $lesson)->with('status', $status);
    }

    public function destroy(Request $request, Lesson $lesson): RedirectResponse
    {
        $courseId = $lesson->course_id;
        $id = $lesson->id;
        $lesson->delete();

        if (class_exists(AuditLogger::class)) {
            app(AuditLogger::class)->log($request->user(), 'lesson.deleted', 'lesson', $id);
        }

        $status = 'تم حذف الدرس.';

        return $this->redirectToCourseContext($request, $status, 'lessons')
            ?? redirect()->route('admin.lessons.index', ['course_id' => $courseId])->with('status', $status);
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
    private function rules(?Lesson $lesson = null): array
    {
        return [
            'course_id' => ['required', 'integer', 'exists:courses,id'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'content_html' => ['nullable', 'string', 'max:200000'],
            'position' => ['nullable', 'integer', 'min:1'],
            'status' => ['required', 'string', Rule::in(['draft', 'published', 'archived', 'scheduled'])],
            'is_locked' => ['nullable', 'boolean'],
            'published_at' => ['nullable', 'date'],
            'quiz_id' => ['nullable', 'integer', 'exists:quizzes,id'],
            'main_media_asset_id' => [
                'nullable',
                'integer',
                'exists:media_assets,id',
                function (string $attribute, mixed $value, \Closure $fail) use ($lesson): void {
                    if ($value === null || $value === '') {
                        return;
                    }
                    if (! $lesson) {
                        $fail('عيّن الفيديو الرئيسي بعد إنشاء الدرس ورفع الفيديو.');

                        return;
                    }
                    $asset = MediaAsset::query()->find($value);
                    if (! $asset || $asset->lesson_id !== $lesson->id || $asset->type !== 'video') {
                        $fail('الفيديو الرئيسي يجب أن يكون فيديو تابعاً لهذا الدرس.');
                    }
                },
            ],
        ];
    }

    private function resolveMainMediaId(?Lesson $lesson, mixed $mediaId): ?int
    {
        if ($mediaId === null || $mediaId === '') {
            return null;
        }

        $id = (int) $mediaId;
        if (! $lesson) {
            return null;
        }

        $asset = MediaAsset::query()->find($id);
        if (! $asset || $asset->lesson_id !== $lesson->id || $asset->type !== 'video') {
            return $lesson->main_media_asset_id;
        }

        return $id;
    }
}
