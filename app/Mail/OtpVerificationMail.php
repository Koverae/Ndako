<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OtpVerificationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $two_factor_code;
    public $user;

    /**
     * Create a new message instance.
     */

    public function __construct($user, $two_factor_code)
    {
        $this->user = $user;
        $this->two_factor_code = $two_factor_code;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Otp Verification Mail',
        );
    }

    /**
     * Get the message content definition.
     */
    // public function content(): Content
    // {
    //     return new Content(
    //         view: 'emails.otp-code',
    //     );
    // }

    public function build()
    {
        return $this->view('emails.otp-code')
            ->subject("Your connection code for your Koverae Space is {$this->two_factor_code}")
            ->with([
                'user' => $this->user,
                'two_factor_code' => $this->two_factor_code,
            ]);
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
