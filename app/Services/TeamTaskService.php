<?php

namespace App\Services;

use App\Models\TeamTask;
use Illuminate\Validation\ValidationException;

class TeamTaskService
{
    public function updateStatus(TeamTask $task, string $status): TeamTask
    {
        $allowed = ['open', 'in_progress', 'done', 'cancelled'];

        if (! in_array($status, $allowed, true)) {
            throw ValidationException::withMessages(['status' => 'Invalid task status.']);
        }

        $task->update(['status' => $status]);

        return $task->fresh();
    }
}