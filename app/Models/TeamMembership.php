<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TeamMembership extends Model
{
    protected $fillable = [
        'user_id', 'team_key', 'role', 'status',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}