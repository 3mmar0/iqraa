<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\Quiz;
use App\Services\AuditLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Modules\Quizzes\Services\QuizAdminService;

class QuizController extends Controller
{
    use Concerns\ReturnsToCourse;

    public function __construct(private readonly QuizAdminService $quizzes)
    {
    }

    public function index(Request $request): View
    {
        $query = Quiz::query()->with('course')->withCount('questions')->latest();

        if ($search = trim((string) $request->query('q', ''))) {
            $query->where('title', 'like', "%{$search}%");
        }

        if ($courseId = $request->query('course_id')) {
            $query->where('course_id', $courseId);
        }

        if ($status = $request->query('status')) {
            $query->where('status', $status);
        }

        $quizzes = $query->paginate(20)->withQueryString();
        $courses = Course::query()->orderBy('title')->get(['id', 'title']);

        return view('admin.quizzes.index', compact('quizzes', 'courses'));
    }

    public function create(): View
    {
        $courses = Course::query()->orderBy('title')->get(['id', 'title']);

        return view('admin.quizzes.create', compact('courses'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate($this->rules(), [
            'required' => 'هذا الحقل مطلوب.',
        ]);

        $validated['show_correct_answers'] = $request->boolean('show_correct_answers');

        if (($validated['status'] ?? '') === 'published') {
            $validated['status'] = 'draft';
        }

        $quiz = Quiz::query()->create($validated);

        if (class_exists(AuditLogger::class)) {
            app(AuditLogger::class)->log($request->user(), 'quiz.created', 'quiz', $quiz->id);
        }

        $status = 'تم إنشاء الاختبار.';
        if ($request->input('status') === 'published') {
            $status = 'تم إنشاء الاختبار كمسودة لأن النشر يتطلب سؤالاً واحداً على الأقل.';
        }

        return $this->redirectToCourseContext($request, $status, 'quizzes')
            ?? redirect()->route('admin.quizzes.show', $quiz)->with('status', $status);
    }

    public function show(Request $request, Quiz $quiz): View
    {
        $tab = $request->query('tab', 'questions');
        $allowed = ['questions', 'attempts', 'statistics', 'settings', 'results', 'leaderboard'];

        if (! in_array($tab, $allowed, true)) {
            $tab = 'questions';
        }

        $quiz->load(['course', 'questions.options']);

        $coursePanel = \App\Support\CoursePanel::fromRequest($request);

        return view('admin.quizzes.show', compact('quiz', 'tab', 'coursePanel'));
    }

    public function edit(Quiz $quiz): View
    {
        $courses = Course::query()->orderBy('title')->get(['id', 'title']);

        return view('admin.quizzes.edit', compact('quiz', 'courses'));
    }

    public function update(Request $request, Quiz $quiz): RedirectResponse
    {
        $validated = $request->validate($this->rules(), [
            'required' => 'هذا الحقل مطلوب.',
        ]);

        $validated['show_correct_answers'] = $request->boolean('show_correct_answers');

        if (($validated['status'] ?? '') === 'published') {
            $this->quizzes->assertCanPublish($quiz);
        }

        $quiz->update($validated);

        if (class_exists(AuditLogger::class)) {
            app(AuditLogger::class)->log($request->user(), 'quiz.updated', 'quiz', $quiz->id);
        }

        $status = 'تم تحديث الاختبار.';

        return $this->redirectToCourseContext($request, $status, 'quizzes')
            ?? redirect()->route('admin.quizzes.show', $quiz)->with('status', $status);
    }

    public function destroy(Request $request, Quiz $quiz): RedirectResponse
    {
        $id = $quiz->id;
        $quiz->delete();

        if (class_exists(AuditLogger::class)) {
            app(AuditLogger::class)->log($request->user(), 'quiz.deleted', 'quiz', $id);
        }

        $status = 'تم حذف الاختبار.';

        return $this->redirectToCourseContext($request, $status, 'quizzes')
            ?? redirect()->route('admin.quizzes.index')->with('status', $status);
    }

    public function duplicate(Request $request, Quiz $quiz): RedirectResponse
    {
        $copy = $this->quizzes->duplicate($quiz, $request->user());

        return redirect()->route('admin.quizzes.show', $copy)->with('status', 'تم نسخ الاختبار.');
    }

    public function publish(Request $request, Quiz $quiz): RedirectResponse
    {
        $this->quizzes->publish($quiz, $request->user());

        return back()->with('status', 'تم نشر الاختبار.');
    }

    public function unpublish(Request $request, Quiz $quiz): RedirectResponse
    {
        $this->quizzes->unpublish($quiz, $request->user());

        return back()->with('status', 'تم إلغاء نشر الاختبار.');
    }

    public function assignCourse(Request $request, Quiz $quiz): RedirectResponse
    {
        $validated = $request->validate([
            'course_id' => ['required', 'integer', 'exists:courses,id'],
        ], [
            'required' => 'هذا الحقل مطلوب.',
        ]);

        $course = Course::query()->findOrFail($validated['course_id']);
        $this->quizzes->assignCourse($quiz, $course, $request->user());

        return back()->with('status', 'تم ربط الاختبار بالمقرر.');
    }

    public function assignLesson(Request $request, Quiz $quiz): RedirectResponse
    {
        $validated = $request->validate([
            'lesson_id' => ['required', 'integer', 'exists:lessons,id'],
        ], [
            'required' => 'هذا الحقل مطلوب.',
        ]);

        $lesson = Lesson::query()->findOrFail($validated['lesson_id']);
        $this->quizzes->assignLesson($quiz, $lesson, $request->user());

        return back()->with('status', 'تم ربط الاختبار بالدرس.');
    }

    public function randomize(Request $request, Quiz $quiz): RedirectResponse
    {
        $this->quizzes->randomizeQuestionPositions($quiz, $request->user());

        return back()->with('status', 'تم خلط ترتيب الأسئلة.');
    }

    /** @return array<string, list<mixed>> */
    private function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'course_id' => ['nullable', 'integer', 'exists:courses,id'],
            'duration_minutes' => ['nullable', 'integer', 'min:1'],
            'status' => ['required', 'string', Rule::in(['draft', 'published'])],
            'show_correct_answers' => ['nullable', 'boolean'],
        ];
    }
}
