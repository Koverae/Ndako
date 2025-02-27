<?php

namespace Modules\Settings\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class MultiChannelNotification  extends Notification
{
    use Queueable;

    public function __construct(public string $message, public string $title)
    {
    }

    // Define delivery channels
    public function via($notifiable)
    {
        return ['database'];
        // return ['mail', 'nexmo', 'database', 'broadcast'];
    }

    // Email channel
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Notification Alert')
            ->line($this->message)
            ->action('View Details', url('/notifications'));
    }

    // SMS channel
    // public function toNexmo($notifiable)
    // {
    //     return (new NexmoMessage)
    //         ->content($this->message);
    // }

    // Database channel
    public function toDatabase($notifiable)
    {
        return [
            'type' => 'alert',
            'title' => $this->title,
            'message' => $this->message,
        ];
    }

    // Broadcast channel (for real-time push notifications)
    public function toBroadcast($notifiable)
    {
        return [
            'type' => 'alert',
            'title' => $this->title,
            'message' => $this->message,
        ];
    }
}
