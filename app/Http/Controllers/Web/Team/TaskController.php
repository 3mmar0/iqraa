<?php

namespace App\Http\Controllers\Web\Team;

use App\Http\Controllers\Controller;
use App\Models\TeamTask;
use App\Services\TeamTaskService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TaskController extends Controller
{
    public function __construct(private TeamTaskService $tasks)
    {
    }

    public function index(): View
    {
        $tasks = TeamTask::query()->with(['assignee', 'creator'])->latest()->get();

        return view('team.tasks.index', compact('tasks'));
    }

    public function update(Request $request, TeamTask $task): RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', 'string', 'in:open,in_progress,done,cancelled'],
        ]);

        $this->tasks->updateStatus($task, $validated['status']);

        return back()->with('status', 'تم تحديث حالة المهمة.');
    }
}