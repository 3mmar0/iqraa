<?php

namespace App\Notifications\Channels;

use Illuminate\Notifications\Notification;

class TelegramChannel
{
    public function send(object $notifiable, Notification $notification): void
    {
        // Phase 1 stub: Telegram delivery not yet wired.
        if (method_exists($notification, 'toTelegram')) {
            $notification->toTelegram($notifiable);
        }
    }
}