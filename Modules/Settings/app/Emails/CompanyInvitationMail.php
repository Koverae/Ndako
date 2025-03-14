<?php

namespace Modules\Settings\Emails;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class CompanyInvitationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $invitationLink, $company;

    /**
     * Create a new message instance.
     *
     * @param string $invitationLink
     */
    public function __construct($invitationLink, $company)
    {
        $this->invitationLink = $invitationLink;
        $this->company = $company;
    }

    /**
     * Build the message.
     */
    public function build(): self
    {
        return $this->subject('Company Invitation')
                    ->view('settings::emails.company-invitation')
                    ->with([
                        'invitationLink' => $this->invitationLink,
                        'company' => $this->company,
                    ]);
    }
}
