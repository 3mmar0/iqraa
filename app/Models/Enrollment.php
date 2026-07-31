<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Enrollment extends Model
{
    protected $fillable = [
        'user_id', 'course_id', 'status', 'source',
        'access_request_id', 'enrolled_at', 'revoked_at',
    ];

    protected function casts(): array
    {
        return [
            'enrolled_at' => 'datetime',
            'revoked_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function isActive(): bool
    {
        return $this->status === 'active';
    }
}
