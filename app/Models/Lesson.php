<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Lesson extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'course_id', 'title', 'description', 'content_html', 'position', 'status',
        'is_locked', 'published_at', 'quiz_id', 'main_media_asset_id',
    ];

    protected function casts(): array
    {
        return [
            'is_locked' => 'boolean',
            'published_at' => 'datetime',
        ];
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function mediaAssets(): HasMany
    {
        return $this->hasMany(MediaAsset::class);
    }

    public function mainMediaAsset(): BelongsTo
    {
        return $this->belongsTo(MediaAsset::class, 'main_media_asset_id');
    }

    public function quiz(): BelongsTo
    {
        return $this->belongsTo(Quiz::class);
    }

    public function hasMainVideo(): bool
    {
        return $this->main_media_asset_id !== null;
    }
}
