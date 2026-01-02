<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AdminNotification extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $user;
    public $subscription;

    /**
     * Create a new message instance.
     *
     * @param \App\Models\User $user
     * @param \App\Models\Business\Subscription $subscription
     * @return void
     */
    public function __construct($user, $subscription)
    {
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
        return $this->subject('A new customer for MuslimLynk')
                    ->view('emails.admin-email')
                    ->with([
                        'user' => $this->user,
                        'subscription' => $this->subscription,
                    ])
                    ->to([
                        'kashif.zubair@amcob.org',
                        'ubaid.syed@kodekaizen.com',
                        'samar.naeem@amcob.org',
                        'kashif.zubair@myadroit.com'
                    ]);
    }
}

