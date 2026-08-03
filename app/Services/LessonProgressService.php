<?php

namespace App\Services;

use App\Models\Lesson;
use App\Models\LessonProgress;
use App\Models\Quiz;
use App\Models\User;

class LessonProgressService
{
    public function markCompleted(User $user, Lesson $lesson): LessonProgress
    {
        $progress = LessonProgress::query()->updateOrCreate(
            [
                'user_id' => $user->id,
                'lesson_id' => $lesson->id,
            ],
            [
                'status' => 'completed',
                'completed_at' => now(),
            ]
        );

        if (! $lesson->hasMainVideo() && $progress->video_completed_at === null) {
            $progress->forceFill(['video_completed_at' => now()])->save();
        }

        return $progress->fresh();
    }

    public function upsertPosition(User $user, Lesson $lesson, int $positionSeconds, bool $markVideoComplete = false): LessonProgress
    {
        $progress = LessonProgress::query()->firstOrNew([
            'user_id' => $user->id,
            'lesson_id' => $lesson->id,
        ]);

        if (! $progress->exists) {
            $progress->status = 'in_progress';
        } elseif ($progress->status === 'not_started') {
            $progress->status = 'in_progress';
        }

        $progress->last_position_seconds = max(0, $positionSeconds);

        if ($markVideoComplete && $progress->video_completed_at === null) {
            $progress->video_completed_at = now();
        }

        $progress->save();

        return $progress->fresh();
    }

    public function markVideoCompleted(User $user, Lesson $lesson): LessonProgress
    {
        return $this->upsertPosition($user, $lesson, 0, true);
    }

    public function progressFor(User $user, Lesson $lesson): ?LessonProgress
    {
        return LessonProgress::query()
            ->where('user_id', $user->id)
            ->where('lesson_id', $lesson->id)
            ->first();
    }

    public function examIsUnlocked(User $user, Lesson $lesson): bool
    {
        $quiz = $lesson->quiz;
        if (! $quiz || $quiz->status !== 'published') {
            return false;
        }

        $progress = $this->progressFor($user, $lesson);

        if ($lesson->hasMainVideo()) {
            return $progress?->watchCompleted() === true;
        }

        return $progress?->status === 'completed' || $progress?->watchCompleted() === true;
    }

    /**
     * If this quiz is linked as a lesson exam, return that lesson; otherwise null (open course quiz).
     */
    public function lessonExamForQuiz(Quiz $quiz): ?Lesson
    {
        return Lesson::query()
            ->where('quiz_id', $quiz->id)
            ->where('status', 'published')
            ->orderBy('id')
            ->first();
    }
}
