<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    protected $fillable = [
        'code', 'discount_type', 'discount_value', 'active', 'usage_limit', 'used_count', 'expires_at',
    ];

    protected function casts(): array
    {
        return [
            'discount_value' => 'decimal:2',
            'active' => 'boolean',
            'expires_at' => 'datetime',
        ];
    }
}