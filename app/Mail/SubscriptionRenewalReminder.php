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
        $this->renewalDate = $subscription->renewal_date ?? $subscription->expires_at;
        $this->amount = $subscription->subscription_amount ?? 0;
        $this->platform = $subscription->platform ?? null;
        $this->subscriptionType = $subscription->subscription_type ?? 'Unknown';
    }

    /**
     * Build the message.
     */
    public function build()
    {
        $platformName = $this->getPlatformName($this->platform);
        $platformLower = strtolower($this->platform ?? '');
        $isFree = strtolower($this->subscriptionType ?? '') === 'free';
        
        return $this->subject("We'd love to keep you with us")
                    ->view('emails.subscription-renewal-reminder')
                    ->with([
                        'userName' => trim(($this->user->first_name ?? '') . ' ' . ($this->user->last_name ?? '')) ?: 'Valued Member',
                        'renewalDate' => $this->renewalDate ? \Carbon\Carbon::parse($this->renewalDate)->format('F d, Y') : 'N/A',
                        'amount' => number_format($this->amount, 2),
                        'platform' => $platformName,
                        'platformLower' => $platformLower,
                        'subscriptionType' => $this->subscriptionType,
                        'manageUrl' => url('/user/subscriptions'),
                        'renewalInstructions' => $this->getRenewalInstructions($platformLower, $isFree),
                        'buttonText' => $this->getButtonText($platformLower),
                    ]);
    }

    /**
     * Get platform-specific renewal instructions
     */
    private function getRenewalInstructions(string $platform, bool $isFree = false): string
    {
        // For free subscriptions without a platform, provide generic instructions
        if ($isFree && (empty($platform) || $platform === 'unknown')) {
            return 'To continue your access, please visit your subscription page and renew your membership. You can upgrade to a paid plan or extend your free membership if eligible.';
        }
        
        return match($platform) {
            'web', 'authorize.net' => 'To continue your access, please visit your subscription page and renew your membership. Your payment will be processed securely through our website.',
            'google', 'google play' => 'To continue your access, please renew your subscription through the Google Play Store. Open the Google Play Store app, go to Subscriptions, and renew your MuslimLynk subscription.',
            'apple', 'apple app store' => 'To continue your access, please renew your subscription through the Apple App Store. Open the App Store app, tap your profile, go to Subscriptions, and renew your MuslimLynk subscription.',
            default => 'To continue your access, please visit your subscription page and renew your membership through your subscription management page.',
        };
    }

    /**
     * Get platform-specific button text
     */
    private function getButtonText(string $platform): string
    {
        return match($platform) {
            'web', 'authorize.net' => 'Renew your membership',
            'google', 'google play', 'apple', 'apple app store' => 'View Subscription Details',
            default => 'Manage Subscription',
        };
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
