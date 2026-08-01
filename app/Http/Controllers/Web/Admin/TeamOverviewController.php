<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Models\Meeting;
use App\Models\TeamTask;
use Illuminate\View\View;

class TeamOverviewController extends Controller
{
    public function index(): View
    {
        $stats = [
            'tasks' => TeamTask::query()->count(),
            'open_tasks' => TeamTask::query()->whereIn('status', ['open', 'pending', 'in_progress'])->count(),
            'meetings' => Meeting::query()->count(),
            'upcoming_meetings' => Meeting::query()->where('starts_at', '>=', now())->count(),
        ];

        return view('admin.team.index', compact('stats'));
    }
}
