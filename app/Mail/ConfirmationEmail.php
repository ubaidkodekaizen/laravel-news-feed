<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ConfirmationEmail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $token;
    public $user;
    public $subscription;

    /**
     * Create a new message instance.
     *
     * @param string $token
     * @param \App\Models\User $user
     * @param \App\Models\Business\Subscription $subscription
     * @return void
     */
    public function __construct($token, $user, $subscription)
    {
        $this->token = $token;
        $this->user = $user;
        $this->subscription = $subscription;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Verify Email & Setup Password')
                    ->view('emails.confirmation-email')
                    ->with([
                        'token' => $this->token,
                        'user' => $this->user,
                        'subscription' => $this->subscription,
                    ]);
    }
}

