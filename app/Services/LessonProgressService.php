<?php

namespace App\Services;

use App\Models\Lesson;
use App\Models\LessonProgress;
use App\Models\User;

class LessonProgressService
{
    public function markCompleted(User $user, Lesson $lesson): LessonProgress
    {
        return LessonProgress::query()->updateOrCreate(
            [
                'user_id' => $user->id,
                'lesson_id' => $lesson->id,
            ],
            [
                'status' => 'completed',
                'completed_at' => now(),
            ]
        );
    }
}
