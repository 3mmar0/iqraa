<?php

namespace App\Notifications;

use App\Models\CourseAccessRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class EnrollmentDecisionNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public CourseAccessRequest $accessRequest,
    ) {}

    /** @return list<string> */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $status = $this->accessRequest->status;
        $courseTitle = $this->accessRequest->course?->title ?? '';

        return (new MailMessage)
            ->subject('قرار طلب الالتحاق')
            ->line("تم تحديث طلب الالتحاق بالدورة: {$courseTitle}")
            ->line("الحالة: {$status}");
    }

    /** @return array<string, mixed> */
    public function toArray(object $notifiable): array
    {
        return [
            'access_request_id' => $this->accessRequest->id,
            'course_id' => $this->accessRequest->course_id,
            'status' => $this->accessRequest->status,
        ];
    }
}