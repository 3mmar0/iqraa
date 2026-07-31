<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReportJob extends Model
{
    protected $fillable = [
        'requester_id', 'type', 'format', 'status', 'file_path', 'finished_at',
    ];

    protected function casts(): array
    {
        return [
            'finished_at' => 'datetime',
        ];
    }

    public function requester(): BelongsTo
    {
        return $this->belongsTo(User::class, 'requester_id');
    }
}