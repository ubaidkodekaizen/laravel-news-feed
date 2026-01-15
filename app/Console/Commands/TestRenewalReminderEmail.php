<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Models\Business\Subscription;
use App\Mail\SubscriptionRenewalReminder;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class TestRenewalReminderEmail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:renewal-reminder-email {email}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send a test renewal reminder email with sample data';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = $this->argument('email');
        
        $this->info("Creating test user and subscription data...");
        
        // Create a test user with sample data
        $testUser = new User();
        $testUser->first_name = 'Ubaid';
        $testUser->last_name = 'Syed';
        $testUser->email = $email;
        $testUser->id = 999999; // Temporary ID for testing
        
        // Create a test subscription with sample data
        $testSubscription = new Subscription();
        $testSubscription->id = 999999;
        $testSubscription->user_id = 999999;
        $testSubscription->subscription_type = 'Annual'; // Can be Annual, Monthly, or Free
        $testSubscription->subscription_amount = 99.99;
        $testSubscription->platform = 'Web'; // Can be Web, Google, Apple, or null for Free
        $testSubscription->renewal_date = Carbon::now()->addDays(30)->format('Y-m-d');
        $testSubscription->expires_at = Carbon::now()->addDays(30);
        $testSubscription->start_date = Carbon::now()->subYear()->format('Y-m-d');
        $testSubscription->status = 'active';
        $testSubscription->auto_renewing = false;
        
        $this->info("Sending test email to: {$email}");
        $this->info("Subscription Type: {$testSubscription->subscription_type}");
        $this->info("Platform: {$testSubscription->platform}");
        $this->info("Renewal Date: {$testSubscription->renewal_date}");
        $this->info("Amount: $" . number_format($testSubscription->subscription_amount, 2));
        $this->newLine();
        
        try {
            Mail::to($email)->send(new SubscriptionRenewalReminder($testUser, $testSubscription));
            $this->info("✓ Test email sent successfully to {$email}!");
        } catch (\Exception $e) {
            $this->error("✗ Failed to send email: " . $e->getMessage());
            return 1;
        }
        
        return 0;
    }
}
