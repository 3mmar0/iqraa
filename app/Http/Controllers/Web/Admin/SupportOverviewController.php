<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use Illuminate\View\View;

class SupportOverviewController extends Controller
{
    public function index(): View
    {
        $stats = [
            'total' => Ticket::query()->count(),
            'open' => Ticket::query()->whereIn('status', ['open', 'pending', 'in_progress'])->count(),
            'closed' => Ticket::query()->where('status', 'closed')->count(),
            'unassigned' => Ticket::query()
                ->whereIn('status', ['open', 'pending', 'in_progress'])
                ->whereNull('assignee_id')
                ->count(),
        ];

        $recentTickets = Ticket::query()
            ->with(['student', 'assignee'])
            ->latest()
            ->limit(5)
            ->get();

        return view('admin.support.index', compact('stats', 'recentTickets'));
    }
}
