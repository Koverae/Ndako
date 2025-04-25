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
    public function __construct($subject, $content, $company, $attachment)
    {
        $this->subject = $subject;
        $this->content = $content;
        $this->company = $company;
        $this->attachment = $attachment;
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
        $email = $this->view('app::emails.template');
    
        if ($this->attachment) {
            $email->attach($this->attachment); // Full path or stream
        }
    
        return $email;
    }
}
