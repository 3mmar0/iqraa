<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LessonProgress extends Model
{
    protected $table = 'lesson_progress';

    protected $fillable = [
        'user_id', 'lesson_id', 'status', 'completed_at', 'last_position_seconds', 'video_completed_at',
    ];

    protected function casts(): array
    {
        return [
            'completed_at' => 'datetime',
            'video_completed_at' => 'datetime',
        ];
    }

    public function lesson(): BelongsTo
    {
        return $this->belongsTo(Lesson::class);
    }

    public function watchCompleted(): bool
    {
        return $this->video_completed_at !== null;
    }
}
