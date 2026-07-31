<?php

namespace App\Http\Controllers\Web\Student;

use App\Http\Controllers\Controller;
use App\Models\FaqArticle;
use App\Models\Ticket;
use App\Services\TicketService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SupportController extends Controller
{
    public function __construct(private TicketService $tickets)
    {
    }

    public function index(Request $request): View
    {
        $tickets = Ticket::query()
            ->where('student_user_id', $request->user()->id)
            ->latest()
            ->get();
        $faqs = FaqArticle::query()->where('published', true)->orderBy('position')->get();

        return view('student.support.index', compact('tickets', 'faqs'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'subject' => ['required', 'string', 'max:255'],
            'body' => ['required', 'string', 'max:5000'],
        ], ['required' => 'هذا الحقل مطلوب.']);

        $this->tickets->open($request->user(), $validated['subject'], $validated['body']);

        return back()->with('status', 'تم فتح التذكرة.');
    }
}
