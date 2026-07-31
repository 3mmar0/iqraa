<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AmbassadorProfile extends Model
{
    protected $fillable = [
        'user_id', 'status', 'referral_count', 'reward_balance',
    ];

    protected function casts(): array
    {
        return [
            'reward_balance' => 'decimal:2',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}