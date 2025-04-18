<?php

namespace Modules\App\Emails;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Address;

class Template extends Mailable
{
    use Queueable, SerializesModels;

    public $subject;
    public $content;
    public $company;
    public $attachment;

    /**
     * Create a new message instance.
     */
    public function __construct($subject, $content, $company)
    {
        $this->subject = $subject;
        $this->content = $content;
        $this->company = $company;
    }

/**
 * Get the message envelope.
 */
public function envelope(): Envelope
{
    return new Envelope(
        // from: new Address('jeffrey@example.com', 'Jeffrey Way'),
        subject: $this->subject,
    );
}

    /**
     * Build the message.
     */
    public function build(): self
    {
        return $this->view('app::emails.template');
    }
}
