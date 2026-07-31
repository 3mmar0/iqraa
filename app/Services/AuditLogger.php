<?php

namespace App\Services;

use App\Models\AuditLog;
use App\Models\User;

class AuditLogger
{
    public function log(
        ?User $actor,
        string $action,
        ?string $targetType = null,
        ?int $targetId = null,
        array $props = [],
    ): AuditLog {
        return AuditLog::query()->create([
            'actor_id' => $actor?->id,
            'action' => $action,
            'target_type' => $targetType,
            'target_id' => $targetId,
            'ip' => request()?->ip(),
            'properties' => $props,
            'created_at' => now(),
        ]);
    }
}