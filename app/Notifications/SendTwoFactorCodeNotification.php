<?php

namespace App\Notifications;

use App\Mail\OtpVerificationMail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\VonageMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\HtmlString;

class SendTwoFactorCodeNotification extends Notification
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
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return config('auth.two_factor.via'); //mail, vonage, database
    }
    

    public function toVonage($notifiable): VonageMessage
    {
        return (new VonageMessage())
            ->content("Your Ndako verification code is: {$notifiable->two_factor_code}")
            ->from('Koverae Technologies');
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable)
    {
        // Generate the two-factor code or retrieve it
        $two_factor_code = $notifiable->two_factor_code;
    
        // Send the custom mailable
        return (new OtpVerificationMail($notifiable, $two_factor_code))
            ->to($notifiable->email);
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
