<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class ViolationStatusUpdated extends Notification
{
    use Queueable;

    public function __construct(
        public array $data
    ) {
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'title' => $this->data['title'] ?? 'Violation Update',
            'message' => $this->data['message'] ?? 'A violation status was updated.',
            'url' => $this->data['url'] ?? null,
            'type' => $this->data['type'] ?? 'violation_status',
            'created_at' => now()->toDateTimeString(),
        ];
    }
}
