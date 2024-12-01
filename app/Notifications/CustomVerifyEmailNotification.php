<?php

namespace App\Notifications;

use App\Mail\CustomVerifyEmail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\URL;
use Illuminate\Auth\Notifications\VerifyEmail as BaseVerifyEmail;

class CustomVerifyEmailNotification extends BaseVerifyEmail implements ShouldQueue
{
    use Queueable;
    

    public function toMail($notifiable)
    {
        // Generate the verification URL
        $verificationUrl = $this->verificationUrl($notifiable);

        // Return the custom Mailable
        return (new CustomVerifyEmail($notifiable, $verificationUrl))->to($notifiable->email);
    }

    protected function verificationUrl($notifiable)
    {
        return URL::temporarySignedRoute(
            'verification.verify', now()->addMinutes(60), ['id' => $notifiable->getKey()]
        );
    }
}
