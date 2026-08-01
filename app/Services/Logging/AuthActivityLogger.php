<?php

namespace App\Services\Logging;

use App\Models\ActivityLog;
use App\Models\User;

class AuthActivityLogger
{
    public function __construct(private readonly ActivityLogger $activity)
    {
    }

    public function log(
        ?User $user,
        string $event,
        ?string $message = null,
        array $context = [],
    ): ActivityLog {
        return $this->activity->log($user, $event, $message, $context, 'authentication');
    }
}
