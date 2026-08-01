<?php

namespace Modules\Quizzes\Services;

use App\Models\Course;
use App\Models\Lesson;
use App\Models\Question;
use App\Models\Quiz;
use App\Models\User;
use App\Services\AuditLogger;
use Illuminate\Support\Facades\DB;

class QuizAdminService
{
    public function __construct(private readonly AuditLogger $audit)
    {
    }

    public function duplicate(Quiz $quiz, ?User $actor = null): Quiz
    {
        return DB::transaction(function () use ($quiz, $actor) {
            $copy = $quiz->replicate(['status']);
            $copy->title = $quiz->title.' (نسخة)';
            $copy->status = 'draft';
            $copy->save();

            foreach ($quiz->questions()->with('options')->get() as $question) {
                $questionCopy = $question->replicate();
                $questionCopy->quiz_id = $copy->id;
                $questionCopy->save();

                foreach ($question->options as $option) {
                    $optionCopy = $option->replicate();
                    $optionCopy->question_id = $questionCopy->id;
                    $optionCopy->save();
                }
            }

            $this->audit->log($actor, 'quiz.duplicated', Quiz::class, $copy->id, [
                'source_id' => $quiz->id,
            ]);

            return $copy->load('questions.options');
        });
    }

    public function publish(Quiz $quiz, ?User $actor = null): Quiz
    {
        $quiz->update(['status' => 'published']);
        $this->audit->log($actor, 'quiz.published', Quiz::class, $quiz->id);

        return $quiz;
    }

    public function unpublish(Quiz $quiz, ?User $actor = null): Quiz
    {
        $quiz->update(['status' => 'draft']);
        $this->audit->log($actor, 'quiz.unpublished', Quiz::class, $quiz->id);

        return $quiz;
    }

    public function assignCourse(Quiz $quiz, Course $course, ?User $actor = null): Quiz
    {
        $quiz->update(['course_id' => $course->id]);
        $this->audit->log($actor, 'quiz.course_assigned', Quiz::class, $quiz->id, [
            'course_id' => $course->id,
        ]);

        return $quiz->fresh('course');
    }

    public function assignLesson(Quiz $quiz, Lesson $lesson, ?User $actor = null): Lesson
    {
        $lesson->update(['quiz_id' => $quiz->id]);
        $this->audit->log($actor, 'quiz.lesson_assigned', Quiz::class, $quiz->id, [
            'lesson_id' => $lesson->id,
        ]);

        return $lesson->fresh('quiz');
    }

    public function randomizeQuestionPositions(Quiz $quiz, ?User $actor = null): Quiz
    {
        $questions = $quiz->questions()->orderBy('position')->get();
        $positions = range(1, $questions->count());
        shuffle($positions);

        DB::transaction(function () use ($questions, $positions, $quiz, $actor) {
            foreach ($questions as $index => $question) {
                Question::query()
                    ->where('id', $question->id)
                    ->update(['position' => $positions[$index]]);
            }

            $this->audit->log($actor, 'quiz.questions_randomized', Quiz::class, $quiz->id);
        });

        return $quiz->fresh('questions');
    }
}
