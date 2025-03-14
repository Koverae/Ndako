<?php

namespace Modules\Settings\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Modules\Settings\Emails\CompanyInvitationMail;

class CompanyInvitationNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct()
    {
        // 
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via($notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail($notifiable)
    {
        $company = current_company();
        $invitationUrl = route('company.invitations.accept', ['token' => $notifiable->token]);

        return (new CompanyInvitationMail($invitationUrl, $company))
            ->to($notifiable->email);

        // return (new MailMessage)
        //             ->subject("Invitation to join $companyName")
        //             ->greeting("Hello " . Auth::user()->name . " !")
        //             ->line("You have been invited by " . Auth::user()->name . " to join the team at $companyName.")
        //             ->action('Accept Invitation', $invitationUrl)
        //             ->line('This invitation will expire on ' . $notifiable->expire_at->format('F j, Y') . '.')
        //             ->line('If you did not expect this invitation, please disregard this email.')
        //             ->salutation('Thank you!');
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray($notifiable): array
    {
        return [];
    }
}
