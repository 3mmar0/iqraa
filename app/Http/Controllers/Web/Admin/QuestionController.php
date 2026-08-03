<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreQuestionRequest;
use App\Http\Requests\Admin\UpdateQuestionRequest;
use App\Models\AttemptAnswer;
use App\Models\Question;
use App\Models\Quiz;
use App\Services\AuditLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\Quizzes\Services\QuizAdminService;

class QuestionController extends Controller
{
    use Concerns\ReturnsToCourse;

    public function __construct(private readonly QuizAdminService $quizzes)
    {
    }

    public function store(StoreQuestionRequest $request, Quiz $quiz): RedirectResponse
    {
        $validated = $request->validated();
        $type = (string) $validated['type'];

        $question = DB::transaction(function () use ($quiz, $validated, $type) {
            $question = Question::query()->create([
                'quiz_id' => $quiz->id,
                'type' => $type,
                'body' => $validated['body'],
                'points' => (int) $validated['points'],
                'position' => $this->quizzes->nextQuestionPosition($quiz),
            ]);

            $this->quizzes->syncQuestionOptions($question, $type, $validated['options'] ?? []);

            return $question;
        });

        if (class_exists(AuditLogger::class)) {
            app(AuditLogger::class)->log($request->user(), 'question.created', 'question', $question->id);
        }

        $status = 'تم إضافة السؤال.';

        return $this->redirectToCourseContext($request, $status, 'quizzes')
            ?? redirect()->route('admin.quizzes.show', [$quiz, 'tab' => 'questions'])->with('status', $status);
    }

    public function update(UpdateQuestionRequest $request, Quiz $quiz, Question $question): RedirectResponse
    {
        $this->ensureQuestionBelongsToQuiz($quiz, $question);

        $validated = $request->validated();
        $type = (string) $validated['type'];

        DB::transaction(function () use ($question, $validated, $type) {
            $question->update([
                'type' => $type,
                'body' => $validated['body'],
                'points' => (int) $validated['points'],
            ]);

            $this->quizzes->syncQuestionOptions($question, $type, $validated['options'] ?? []);
        });

        if (class_exists(AuditLogger::class)) {
            app(AuditLogger::class)->log($request->user(), 'question.updated', 'question', $question->id);
        }

        $status = 'تم تحديث السؤال.';

        return $this->redirectToCourseContext($request, $status, 'quizzes')
            ?? redirect()->route('admin.quizzes.show', [$quiz, 'tab' => 'questions'])->with('status', $status);
    }

    public function destroy(Request $request, Quiz $quiz, Question $question): RedirectResponse
    {
        $this->ensureQuestionBelongsToQuiz($quiz, $question);

        if (AttemptAnswer::query()->where('question_id', $question->id)->exists()) {
            $status = 'لا يمكن حذف سؤال مرتبط بمحاولات طلاب.';

            return $this->redirectToCourseContext($request, $status, 'quizzes')
                ?? redirect()->route('admin.quizzes.show', [$quiz, 'tab' => 'questions'])->with('status', $status);
        }

        $id = $question->id;
        $question->delete();

        if (class_exists(AuditLogger::class)) {
            app(AuditLogger::class)->log($request->user(), 'question.deleted', 'question', $id);
        }

        $status = 'تم حذف السؤال.';

        return $this->redirectToCourseContext($request, $status, 'quizzes')
            ?? redirect()->route('admin.quizzes.show', [$quiz, 'tab' => 'questions'])->with('status', $status);
    }

    public function reorder(Request $request, Quiz $quiz): RedirectResponse
    {
        $validated = $request->validate([
            'question_ids' => ['required', 'array', 'min:1'],
            'question_ids.*' => ['integer', 'exists:questions,id'],
            'return_to' => ['nullable', 'string'],
            'return_course_id' => ['nullable', 'integer'],
            'return_tab' => ['nullable', 'string'],
        ], [
            'required' => 'هذا الحقل مطلوب.',
        ]);

        $ids = array_map('intval', $validated['question_ids']);
        $ownedIds = $quiz->questions()->whereIn('id', $ids)->pluck('id')->all();

        if (count($ownedIds) !== count($ids)) {
            $status = 'تعذر إعادة ترتيب الأسئلة.';

            return $this->redirectToCourseContext($request, $status, 'quizzes')
                ?? redirect()->route('admin.quizzes.show', [$quiz, 'tab' => 'questions'])->with('status', $status);
        }

        DB::transaction(function () use ($ids) {
            foreach ($ids as $index => $id) {
                Question::query()->where('id', $id)->update(['position' => $index + 1]);
            }
        });

        if (class_exists(AuditLogger::class)) {
            app(AuditLogger::class)->log($request->user(), 'quiz.questions_reordered', Quiz::class, $quiz->id);
        }

        $status = 'تم تحديث ترتيب الأسئلة.';

        return $this->redirectToCourseContext($request, $status, 'quizzes')
            ?? redirect()->route('admin.quizzes.show', [$quiz, 'tab' => 'questions'])->with('status', $status);
    }

    private function ensureQuestionBelongsToQuiz(Quiz $quiz, Question $question): void
    {
        abort_unless($question->quiz_id === $quiz->id, 404);
    }
}
