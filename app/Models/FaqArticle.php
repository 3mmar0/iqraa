<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FaqArticle extends Model
{
    protected $fillable = [
        'title', 'body', 'published', 'position',
    ];

    protected function casts(): array
    {
        return [
            'published' => 'boolean',
        ];
    }
}