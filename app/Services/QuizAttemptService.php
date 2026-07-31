<?php

namespace App\Services;

use App\Models\Quiz;
use App\Models\QuizAttempt;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class QuizAttemptService
{
    public function start(User $user, Quiz $quiz): QuizAttempt
    {
        return QuizAttempt::query()->create([
            'user_id' => $user->id,
            'quiz_id' => $quiz->id,
            'started_at' => now(),
            'status' => 'in_progress',
        ]);
    }

    public function submit(QuizAttempt $attempt, array $answers): QuizAttempt
    {
        return DB::transaction(function () use ($attempt, $answers) {
            $quiz = $attempt->quiz()->with('questions.options')->firstOrFail();
            $totalPoints = 0;
            $earned = 0;

            foreach ($quiz->questions as $question) {
                $totalPoints += $question->points;
                $selectedId = $answers[(string) $question->id] ?? $answers[$question->id] ?? null;
                $correctIds = $question->options->where('is_correct', true)->pluck('id')->all();
                $isCorrect = $selectedId && in_array((int) $selectedId, array_map('intval', $correctIds), true);

                if ($isCorrect) {
                    $earned += $question->points;
                }

                $attempt->answers()->updateOrCreate(
                    ['question_id' => $question->id],
                    [
                        'selected' => ['option_id' => $selectedId],
                        'is_correct' => (bool) $isCorrect,
                    ]
                );
            }

            $score = $totalPoints > 0 ? round(($earned / $totalPoints) * 100, 2) : 0;

            $attempt->update([
                'status' => 'submitted',
                'submitted_at' => now(),
                'score' => $score,
            ]);

            return $attempt->fresh(['answers.question.options', 'quiz']);
        });
    }
}
