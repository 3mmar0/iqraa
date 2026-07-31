<?php

namespace App\Http\Controllers\Web\Support;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Services\TicketService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TicketController extends Controller
{
    public function __construct(private TicketService $tickets)
    {
    }

    public function index(): View
    {
        $tickets = Ticket::query()->with(['student', 'assignee'])->latest()->paginate(20);

        return view('support.tickets.index', compact('tickets'));
    }

    public function show(Ticket $ticket): View
    {
        $ticket->load(['messages.sender', 'student', 'assignee']);

        return view('support.tickets.show', compact('ticket'));
    }

    public function reply(Request $request, Ticket $ticket): RedirectResponse
    {
        $validated = $request->validate([
            'body' => ['required', 'string', 'max:5000'],
        ]);

        $this->tickets->reply($ticket, $request->user(), $validated['body']);

        return back()->with('status', 'تم إرسال الرد.');
    }

    public function close(Ticket $ticket): RedirectResponse
    {
        $this->tickets->close($ticket);

        return back()->with('status', 'تم إغلاق التذكرة.');
    }
}