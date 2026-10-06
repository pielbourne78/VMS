<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class StudentQuizSubmissionNotification extends Notification
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
            'title' => $this->data['title'] ?? 'Quiz submission received',
            'message' => $this->data['message'] ?? 'A student submitted a quiz response for review.',
            'url' => $this->data['url'] ?? route('admin.appeals'),
            'type' => $this->data['type'] ?? 'student_quiz_submission',
            'created_at' => now()->toDateTimeString(),
        ];
    }
}
