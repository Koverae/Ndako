<?php

namespace Modules\Admin\Emails;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class NdakoAppKeyMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $appKey;

    /**
     * Create a new message instance.
     */
    public function __construct(User $user, string $appKey)
    {
        $this->user = $user;
        $this->appKey = $appKey;
    }

    /**
     * Build the message.
     */
    public function build(): self
    {
        return $this->subject('Your Ndako On-Premise APP Key')
                    ->view('admin::emails.ndako_app_key')
                    ->with([
                        'user' => $this->user,
                        'appKey' => $this->appKey,
                        'downloadUrl' => 'https://ndako.tech/download',
                    ]);
    }
}
