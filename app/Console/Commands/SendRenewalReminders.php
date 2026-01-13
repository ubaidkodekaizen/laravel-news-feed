<?php

namespace App\Console\Commands;

use App\Models\Business\Subscription;
use App\Models\SchedulerLog;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Mail\SubscriptionRenewalReminder;

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
    protected $description = 'Send email reminders to users whose subscriptions are renewing within 3 days';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $startTime = microtime(true);
        $ranAt = now();
        
        $this->info('Starting subscription renewal reminder process...');
        $this->newLine();

        $totalProcessed = 0;
        $totalSent = 0;
        $totalSkipped = 0;
        $totalErrors = 0;
        $errorMessage = null;
        $errorTrace = null;
        $status = 'success';
        $usersSent = [];

        try {
            // Calculate the date 3 days from now
            $threeDaysFromNow = Carbon::now()->addDays(3)->format('Y-m-d');
            $today = Carbon::now()->format('Y-m-d');

            // Find all active subscriptions that are renewing within 3 days
            $subscriptions = Subscription::with('user')
                ->where('status', 'active')
                ->whereNotNull('renewal_date')
                ->whereDate('renewal_date', '>=', $today)
                ->whereDate('renewal_date', '<=', $threeDaysFromNow)
                ->where(function($query) {
                    // Only send if reminder hasn't been sent in the last 24 hours
                    $query->whereNull('renewal_reminder_sent_at')
                          ->orWhere('renewal_reminder_sent_at', '<', Carbon::now()->subDay());
                })
                ->get();

            if ($subscriptions->isEmpty()) {
                $this->info('No subscriptions found that need renewal reminders.');
                $status = 'success';
            } else {
                $this->info("Found {$subscriptions->count()} subscription(s) that need renewal reminders.");

                foreach ($subscriptions as $subscription) {
                    $totalProcessed++;
                    
                    try {
                        // Skip if user doesn't exist or email is invalid
                        if (!$subscription->user || !$subscription->user->email) {
                            $this->warn("Subscription ID {$subscription->id} - User or email not found, skipping");
                            $totalSkipped++;
                            continue;
                        }

                        // Skip if subscription is cancelled (shouldn't happen but safety check)
                        if ($subscription->status !== 'active') {
                            $this->warn("Subscription ID {$subscription->id} - Not active, skipping");
                            $totalSkipped++;
                            continue;
                        }

                        // Get current pricing from subscription (not old pricing)
                        $currentAmount = $subscription->subscription_amount ?? 0;
                        
                        // Send the reminder email
                        Mail::to($subscription->user->email)
                            ->send(new SubscriptionRenewalReminder($subscription->user, $subscription));

                        // Update subscription to mark reminder as sent
                        $subscription->renewal_reminder_sent_at = now();
                        $subscription->save();

                        $totalSent++;
                        $usersSent[] = [
                            'user_id' => $subscription->user->id,
                            'email' => $subscription->user->email,
                            'name' => trim(($subscription->user->first_name ?? '') . ' ' . ($subscription->user->last_name ?? '')) ?: 'N/A',
                            'subscription_id' => $subscription->id,
                            'platform' => $subscription->platform ?? 'Unknown',
                            'renewal_date' => $subscription->renewal_date,
                            'amount' => $currentAmount,
                        ];

                        $this->info("Reminder sent to {$subscription->user->email} for subscription ID {$subscription->id} (Renewal: {$subscription->renewal_date})");
                    } catch (\Exception $e) {
                        $this->error("Error sending reminder for subscription ID {$subscription->id}: " . $e->getMessage());
                        Log::error("Renewal reminder error for subscription {$subscription->id}: " . $e->getMessage());
                        $totalErrors++;
                    }
                }
            }

            // Determine status
            if ($totalErrors > 0 && $totalSent > 0) {
                $status = 'partial';
            } elseif ($totalErrors > 0) {
                $status = 'failed';
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

        $executionTime = (int) ((microtime(true) - $startTime) * 1000);

        $this->newLine();
        $this->info("=== Renewal Reminder Process Complete ===");
        $this->info("Total Processed: {$totalProcessed}");
        $this->info("Reminders Sent: {$totalSent}");
        $this->info("Skipped: {$totalSkipped}");
        $this->info("Errors: {$totalErrors}");
        $this->info("Execution Time: {$executionTime}ms");

        // Build result detail
        $resultDetail = "=== Renewal Reminder Results ===\n";
        $resultDetail .= "Total Processed: {$totalProcessed}\n";
        $resultDetail .= "Reminders Sent: {$totalSent}\n";
        $resultDetail .= "Skipped: {$totalSkipped}\n";
        $resultDetail .= "Errors: {$totalErrors}\n";
        if (!empty($usersSent)) {
            $resultDetail .= "\n=== Users Notified ===\n";
            foreach ($usersSent as $user) {
                $resultDetail .= "User: {$user['name']} ({$user['email']}) - Platform: {$user['platform']} - Renewal: {$user['renewal_date']} - Amount: \${$user['amount']}\n";
            }
        }

        // Build structured result data
        $resultData = [
            'total_processed' => $totalProcessed,
            'total_sent' => $totalSent,
            'total_skipped' => $totalSkipped,
            'total_errors' => $totalErrors,
            'users_sent' => $usersSent,
        ];

        // Log to database
        SchedulerLog::create([
            'scheduler' => 'subscriptions:send-renewal-reminders',
            'command' => 'subscriptions:send-renewal-reminders',
            'status' => $status,
            'result_detail' => $resultDetail,
            'result_data' => $resultData,
            'records_processed' => $totalProcessed,
            'records_updated' => $totalSent,
            'records_failed' => $totalErrors,
            'error_message' => $errorMessage,
            'error_trace' => $errorTrace,
            'execution_time_ms' => $executionTime,
            'ran_at' => $ranAt,
        ]);

        return $status === 'failed' ? 1 : 0;
    }
}
