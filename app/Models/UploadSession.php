<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UploadSession extends Model
{
    use HasUuids;

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'user_id',
        'course_id',
        'purpose',
        'original_name',
        'mime',
        'total_size',
        'chunk_size',
        'total_chunks',
        'received_chunks',
        'status',
        'temp_key',
        'client_fingerprint',
    ];

    protected function casts(): array
    {
        return [
            'received_chunks' => 'array',
            'total_size' => 'integer',
            'chunk_size' => 'integer',
            'total_chunks' => 'integer',
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

    /** @return list<int> */
    public function receivedChunkIndexes(): array
    {
        return array_values(array_map('intval', $this->received_chunks ?? []));
    }

    public function markChunkReceived(int $index): void
    {
        $chunks = $this->receivedChunkIndexes();
        if (! in_array($index, $chunks, true)) {
            $chunks[] = $index;
            sort($chunks);
            $this->received_chunks = $chunks;
            $this->save();
        }
    }

    public function uploadedBytes(): int
    {
        $received = count($this->receivedChunkIndexes());
        if ($received >= $this->total_chunks) {
            return (int) $this->total_size;
        }

        return min((int) $this->total_size, $received * (int) $this->chunk_size);
    }

    public function isComplete(): bool
    {
        return count($this->receivedChunkIndexes()) >= (int) $this->total_chunks;
    }
}
