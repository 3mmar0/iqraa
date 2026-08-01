<?php

namespace App\Services\Logging;

use App\Models\ActivityLog;
use App\Models\User;

class ActivityLogger
{
    public function log(
        ?User $user,
        string $event,
        ?string $message = null,
        array $context = [],
        ?string $channel = 'activity',
    ): ActivityLog {
        return ActivityLog::query()->create([
            'user_id' => $user?->id,
            'channel' => $channel,
            'event' => $event,
            'message' => $message,
            'context' => $context,
            'ip' => request()?->ip(),
        ]);
    }
}
