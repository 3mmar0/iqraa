<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TelegramGroup extends Model
{
    protected $fillable = [
        'title', 'chat_id', 'course_id', 'invite_link', 'invite_expires_at', 'status',
    ];

    protected function casts(): array
    {
        return [
            'invite_expires_at' => 'datetime',
        ];
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }
}
