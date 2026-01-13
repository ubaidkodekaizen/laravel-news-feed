<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Business\Subscription;
use App\Models\User;

class SubscriptionRenewalReminder extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $subscription;
    public $renewalDate;
    public $amount;
    public $platform;
    public $subscriptionType;

    /**
     * Create a new message instance.
     */
    public function __construct(User $user, Subscription $subscription)
    {
        $this->user = $user;
        $this->subscription = $subscription;
        $this->renewalDate = $subscription->renewal_date;
        $this->amount = $subscription->subscription_amount ?? 0;
        $this->platform = $subscription->platform ?? 'Unknown';
        $this->subscriptionType = $subscription->subscription_type ?? 'Unknown';
    }

    /**
     * Build the message.
     */
    public function build()
    {
        $platformName = $this->getPlatformName($this->platform);
        
        return $this->subject("Your {$this->subscriptionType} Subscription Renews Soon - MuslimLynk")
                    ->view('emails.subscription-renewal-reminder')
                    ->with([
                        'userName' => trim(($this->user->first_name ?? '') . ' ' . ($this->user->last_name ?? '')) ?: 'Valued Member',
                        'renewalDate' => \Carbon\Carbon::parse($this->renewalDate)->format('F d, Y'),
                        'amount' => number_format($this->amount, 2),
                        'platform' => $platformName,
                        'subscriptionType' => $this->subscriptionType,
                        'manageUrl' => url('/user/subscriptions'),
                    ]);
    }

    /**
     * Get user-friendly platform name
     */
    private function getPlatformName(string $platform): string
    {
        return match(strtolower($platform)) {
            'web' => 'Web/Authorize.Net',
            'google' => 'Google Play',
            'apple' => 'Apple App Store',
            default => $platform,
        };
    }
}
