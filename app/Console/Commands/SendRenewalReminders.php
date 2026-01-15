<?php

namespace App\Console\Commands;

use App\Models\Business\Subscription;
use App\Models\User;
use App\Models\SchedulerLog;
use App\Mail\SubscriptionRenewalReminder;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class SendRenewalReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'subscriptions:send-renewal-reminders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send renewal reminder emails to users with subscriptions expiring soon';

    /**
     * Get list of excluded email addresses from environment
     * These emails will not receive renewal reminders
     */
    private function getExcludedEmails(): array
    {
        $excluded = env('SCHEDULER_EXCLUDED_EMAILS', '');
        if (empty($excluded)) {
            return [];
        }
        
        // Split by comma and trim each email
        return array_map('trim', explode(',', $excluded));
    }

    /**
     * Check if email should be excluded
     */
    private function shouldSkipEmail($userEmail, array $excludedEmails): bool
    {
        if (empty($excludedEmails) || !$userEmail) {
            return false;
        }
        
        // Case-insensitive comparison
        $userEmailLower = strtolower(trim($userEmail));
        $excludedEmailsLower = array_map('strtolower', $excludedEmails);
        
        return in_array($userEmailLower, $excludedEmailsLower);
    }

    /**
     * Calculate expiration date for a subscription
     */
    private function getExpirationDate(Subscription $subscription, ?User $user = null): ?Carbon
    {
        // First, check if expires_at or renewal_date exists (works for both paid and free)
        if ($subscription->expires_at) {
            return Carbon::parse($subscription->expires_at);
        }
        if ($subscription->renewal_date) {
            return Carbon::parse($subscription->renewal_date);
        }
        
        // For paid subscriptions (Annual/Monthly), if no dates exist, return null
        if ($subscription->subscription_type !== 'Free' && $subscription->subscription_type !== null) {
            return null;
        }
        
        // For free subscriptions, calculate from start_date + duration
        if ($subscription->subscription_type === 'Free' && $subscription->start_date) {
            $startDate = Carbon::parse($subscription->start_date);
            
            // Get duration from user, default to 90 days if null
            $duration = $user && $user->duration ? $user->duration : 90;
            
            // Convert duration to integer (handle string "30", "60", "90")
            $durationDays = (int) $duration;
            
            // Ensure minimum of 1 day
            if ($durationDays < 1) {
                $durationDays = 90;
            }
            
            return $startDate->copy()->addDays($durationDays);
        }
        
        return null;
    }

    /**
     * Get reminder days before expiration based on subscription type
     */
    private function getReminderDays(string $subscriptionType): array
    {
        $subscriptionTypeLower = strtolower($subscriptionType ?? '');
        
        if (strpos($subscriptionTypeLower, 'annual') !== false || strpos($subscriptionTypeLower, 'yearly') !== false || strpos($subscriptionTypeLower, 'year') !== false) {
            return [30]; // Annual: 30 days before
        } elseif (strpos($subscriptionTypeLower, 'monthly') !== false || strpos($subscriptionTypeLower, 'month') !== false) {
            return [5]; // Monthly: 5 days before
        } elseif ($subscriptionType === 'Free') {
            // For free subscriptions, we'll determine based on duration
            return [3, 7, 10]; // Will be filtered based on duration
        }
        
        return [];
    }

    /**
     * Get reminder days for free subscription based on duration
     */
    private function getFreeReminderDays(?string $duration): array
    {
        $durationDays = (int) ($duration ?? 90);
        
        if ($durationDays === 30) {
            return [3]; // 30-day free: 3 days before
        } elseif ($durationDays === 60) {
            return [7]; // 60-day free: 7 days before
        } else {
            // 90 days or null (defaults to 90)
            return [10]; // 90-day free: 10 days before
        }
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $startTime = microtime(true);
        $ranAt = now();
        
        $this->info('Starting renewal reminder email process...');
        $this->newLine();
        
        $excludedEmails = $this->getExcludedEmails();
        if (!empty($excludedEmails)) {
            $this->info('Excluded emails from .env (will not receive reminders): ' . implode(', ', $excludedEmails));
            $this->newLine();
        }
        
        $today = Carbon::today();
        $sentCount = 0;
        $skippedCount = 0;
        $errorCount = 0;
        $status = 'success';
        $errorMessage = null;
        $errorTrace = null;
        $sentEmails = [];
        $failedEmails = [];
        
        try {
            // Get all active subscriptions where auto_renewing is false or null, OR free subscriptions (which may not have auto_renewing set)
            $subscriptions = Subscription::with('user')
                ->where('status', 'active')
                ->where(function ($query) {
                    $query->where(function ($q) {
                        // Paid subscriptions: must have auto_renewing = false or null
                        $q->where('auto_renewing', false)
                          ->orWhereNull('auto_renewing');
                    })
                    ->orWhere('subscription_type', 'Free'); // Include free subscriptions regardless of auto_renewing
                })
                ->whereNotNull('user_id')
                ->get();
            
            $this->info("Found {$subscriptions->count()} active subscription(s) without auto-renewal.");
            $this->newLine();
            
            foreach ($subscriptions as $subscription) {
                try {
                $user = $subscription->user;
                
                // Skip if user doesn't exist or is deleted
                if (!$user || $user->trashed()) {
                    $skippedCount++;
                    continue;
                }
                
                // Skip excluded emails
                if ($this->shouldSkipEmail($user->email, $excludedEmails)) {
                    $skippedCount++;
                    continue;
                }
                
                // Calculate expiration date
                $expirationDate = $this->getExpirationDate($subscription, $user);
                
                if (!$expirationDate) {
                    $skippedCount++;
                    $this->line("Skipping subscription ID {$subscription->id} - Could not determine expiration date");
                    continue;
                }
                
                // Get reminder days based on subscription type
                $reminderDays = $this->getReminderDays($subscription->subscription_type ?? '');
                
                // For free subscriptions, adjust based on duration
                if ($subscription->subscription_type === 'Free') {
                    $reminderDays = $this->getFreeReminderDays($user->duration);
                }
                
                if (empty($reminderDays)) {
                    $skippedCount++;
                    continue;
                }
                
                // Skip if expiration date is in the past
                if ($expirationDate->isPast()) {
                    $skippedCount++;
                    continue;
                }
                
                // Check if we should send reminder today
                $shouldSend = false;
                $daysUntilExpiration = $today->diffInDays($expirationDate, false);
                
                // Only proceed if expiration is in the future
                if ($daysUntilExpiration < 0) {
                    $skippedCount++;
                    continue;
                }
                
                foreach ($reminderDays as $daysBefore) {
                    if ($daysUntilExpiration === $daysBefore) {
                        // Check if reminder was already sent for this expiration period
                        // We'll track by checking if renewal_reminder_sent_at is within the last 2 days
                        // This prevents duplicate sends if the command runs multiple times
                        $lastSent = $subscription->renewal_reminder_sent_at 
                            ? Carbon::parse($subscription->renewal_reminder_sent_at) 
                            : null;
                        
                        // Only send if not sent in the last 2 days (to avoid duplicates)
                        if (!$lastSent || $lastSent->lt($today->copy()->subDays(2))) {
                            $shouldSend = true;
                            break;
                        }
                    }
                }
                
                if (!$shouldSend) {
                    continue;
                }
                
                // Send the email
                try {
                    Mail::to($user->email)->send(new SubscriptionRenewalReminder($user, $subscription));
                    
                    // Update the reminder sent timestamp
                    $subscription->renewal_reminder_sent_at = now();
                    $subscription->save();
                    
                    $sentCount++;
                    $sentEmails[] = $user->email;
                    $this->info("✓ Sent renewal reminder to {$user->email} (Subscription ID: {$subscription->id}, Expires: {$expirationDate->format('Y-m-d')}, Days until expiration: {$daysUntilExpiration})");
                } catch (\Exception $e) {
                    $errorCount++;
                    $failedEmails[$user->email] = $e->getMessage();
                    $this->error("✗ Failed to send email to {$user->email} (Subscription ID: {$subscription->id}): " . $e->getMessage());
                    Log::error('Renewal reminder email failed', [
                        'user_id' => $user->id,
                        'subscription_id' => $subscription->id,
                        'error' => $e->getMessage(),
                    ]);
                }
                
            } catch (\Exception $e) {
                $errorCount++;
                if (isset($user) && $user && $user->email) {
                    $failedEmails[$user->email] = $e->getMessage();
                }
                $this->error("✗ Error processing subscription ID {$subscription->id}: " . $e->getMessage());
                Log::error('Renewal reminder processing error', [
                    'subscription_id' => $subscription->id,
                    'error' => $e->getMessage(),
                ]);
                }
            }
        } catch (\Exception $e) {
            $status = 'failed';
            $errorMessage = $e->getMessage();
            $errorTrace = $e->getTraceAsString();
            $this->error('Fatal error: ' . $errorMessage);
            Log::error('Renewal reminder scheduler failed: ' . $errorMessage, [
                'trace' => $errorTrace,
            ]);
        }
        
        $executionTime = (int) ((microtime(true) - $startTime) * 1000); // Convert to milliseconds
        
        $this->newLine();
        $this->info("=== Summary ===");
        $this->info("Emails sent: {$sentCount}");
        $this->info("Skipped: {$skippedCount}");
        $this->info("Errors: {$errorCount}");
        $this->info("Execution Time: {$executionTime}ms");
        
        // Determine status
        if ($errorCount > 0 && $sentCount > 0) {
            $status = 'partial';
        } elseif ($errorCount > 0) {
            $status = 'failed';
        }
        
        // Build detailed result text
        $resultDetail = $this->buildResultDetail($sentCount, $skippedCount, $errorCount, $sentEmails, $failedEmails);
        
        // Build structured result data (JSON)
        $resultData = [
            'emails_sent' => $sentCount,
            'emails_skipped' => $skippedCount,
            'emails_failed' => $errorCount,
            'sent_emails' => $sentEmails,
            'failed_emails' => $failedEmails,
        ];
        
        // Log to database
        try {
            SchedulerLog::create([
                'scheduler' => 'subscriptions:send-renewal-reminders',
                'command' => 'subscriptions:send-renewal-reminders',
                'status' => $status,
                'result_detail' => $resultDetail,
                'result_data' => $resultData,
                'records_processed' => $sentCount + $skippedCount + $errorCount,
                'records_updated' => $sentCount,
                'records_failed' => $errorCount,
                'error_message' => $errorMessage,
                'error_trace' => $errorTrace,
                'execution_time_ms' => $executionTime,
                'ran_at' => $ranAt,
            ]);
        } catch (\Exception $e) {
            $this->error('Failed to log to database: ' . $e->getMessage());
            Log::error('Failed to log renewal reminders to scheduler_logs', [
                'error' => $e->getMessage(),
            ]);
        }
        
        return $status === 'failed' ? 1 : 0;
    }

    /**
     * Build detailed result string for logging
     */
    private function buildResultDetail(int $sentCount, int $skippedCount, int $errorCount, array $sentEmails, array $failedEmails): string
    {
        $details = [];
        
        $details[] = "=== Renewal Reminder Email Results ===";
        $details[] = "Emails Sent: {$sentCount}";
        $details[] = "Emails Skipped: {$skippedCount}";
        $details[] = "Emails Failed: {$errorCount}";
        
        if (!empty($sentEmails)) {
            $details[] = "";
            $details[] = "Successfully Sent Emails:";
            foreach ($sentEmails as $email) {
                $details[] = "  - {$email}";
            }
        }
        
        if (!empty($failedEmails)) {
            $details[] = "";
            $details[] = "Failed Emails:";
            foreach ($failedEmails as $email => $error) {
                $details[] = "  - {$email}: {$error}";
            }
        }
        
        return implode("\n", $details);
    }
}
