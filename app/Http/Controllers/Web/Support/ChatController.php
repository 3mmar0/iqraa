<?php

namespace App\Http\Controllers\Web\Support;

use App\Http\Controllers\Controller;
use App\Models\TicketMessage;
use Illuminate\View\View;

class ChatController extends Controller
{
    public function index(): View
    {
        $messages = TicketMessage::query()
            ->with(['ticket', 'sender'])
            ->where('channel', 'live_chat')
            ->latest()
            ->limit(100)
            ->get();

        return view('support.chat.index', compact('messages'));
    }
}