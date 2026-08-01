<?php

namespace Modules\Notifications\Services;

use App\Models\Course;
use App\Models\TelegramGroup;
use App\Models\User;
use App\Services\AuditLogger;
use Illuminate\Support\Str;

class TelegramAdminService
{
    public function __construct(private readonly AuditLogger $audit)
    {
    }

    public function createGroup(array $data, ?User $actor = null): TelegramGroup
    {
        $group = TelegramGroup::query()->create([
            'title' => $data['title'],
            'chat_id' => $data['chat_id'] ?? null,
            'course_id' => $data['course_id'] ?? null,
            'status' => $data['status'] ?? 'active',
        ]);

        $this->audit->log($actor, 'telegram.group_created', TelegramGroup::class, $group->id);

        return $group;
    }

    public function generateInvite(TelegramGroup $group, ?User $actor = null, int $ttlHours = 24): TelegramGroup
    {
        $token = Str::random(32);
        $group->update([
            'invite_link' => 'https://t.me/+stub_'.$token,
            'invite_expires_at' => now()->addHours($ttlHours),
        ]);

        $this->audit->log($actor, 'telegram.invite_generated', TelegramGroup::class, $group->id);

        return $group;
    }

    public function expireLink(TelegramGroup $group, ?User $actor = null): TelegramGroup
    {
        $group->update([
            'invite_link' => null,
            'invite_expires_at' => now(),
        ]);

        $this->audit->log($actor, 'telegram.invite_expired', TelegramGroup::class, $group->id);

        return $group;
    }

    /**
     * Stub: queue or dispatch real Telegram API integration in a later phase.
     *
     * @param  array<string, mixed>  $payload
     */
    public function sendAnnouncement(TelegramGroup $group, string $message, array $payload = [], ?User $actor = null): bool
    {
        $this->audit->log($actor, 'telegram.announcement_stub', TelegramGroup::class, $group->id, [
            'message' => $message,
            'payload' => $payload,
            'chat_id' => $group->chat_id,
        ]);

        return true;
    }

    public function linkCourse(TelegramGroup $group, Course $course, ?User $actor = null): TelegramGroup
    {
        $group->update(['course_id' => $course->id]);
        $this->audit->log($actor, 'telegram.course_linked', TelegramGroup::class, $group->id, [
            'course_id' => $course->id,
        ]);

        return $group->fresh('course');
    }
}
