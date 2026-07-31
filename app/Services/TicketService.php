<?php

namespace App\Services;

use App\Models\Ticket;
use App\Models\TicketMessage;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class TicketService
{
    public function open(User $student, string $subject, string $body): Ticket
    {
        return DB::transaction(function () use ($student, $subject, $body) {
            $ticket = Ticket::query()->create([
                'student_user_id' => $student->id,
                'subject' => $subject,
                'status' => 'open',
            ]);

            TicketMessage::query()->create([
                'ticket_id' => $ticket->id,
                'sender_id' => $student->id,
                'body' => $body,
                'channel' => 'ticket',
            ]);

            return $ticket->load('messages');
        });
    }

    public function reply(Ticket $ticket, User $sender, string $body, string $channel = 'ticket'): TicketMessage
    {
        if ($ticket->status === 'closed') {
            throw ValidationException::withMessages(['ticket' => 'Cannot reply to a closed ticket.']);
        }

        return TicketMessage::query()->create([
            'ticket_id' => $ticket->id,
            'sender_id' => $sender->id,
            'body' => $body,
            'channel' => $channel,
        ]);
    }

    public function close(Ticket $ticket): Ticket
    {
        $ticket->update(['status' => 'closed']);

        return $ticket->fresh();
    }
}