<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class AdminActivityNotification extends Notification
{
    use Queueable;

    /**
     * @param  array{type: string, title: string, message: string, url: ?string}  $data
     */
    public function __construct(private array $data) {}

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array{type: string, title: string, message: string, url: ?string}
     */
    public function toArray(object $notifiable): array
    {
        return $this->data;
    }
}
