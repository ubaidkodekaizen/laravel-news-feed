<?php

namespace App\Console\Commands;

use App\Models\Subscription;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use net\authorize\api\contract\v1 as AnetAPI;
use net\authorize\api\controller as AnetController;

class SyncAuthorizeNetRenewalDates extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'subscriptions:sync-renewal-dates';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync renewal dates from Authorize.Net for Web platform subscriptions';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting to sync renewal dates from Authorize.Net...');

        // Get all active subscriptions with platform = 'Web'
        $webSubscriptions = Subscription::where('platform', 'Web')
            ->where('status', 'active')
            ->whereNotNull('transaction_id')
            ->get();

        if ($webSubscriptions->isEmpty()) {
            $this->info('No Web platform subscriptions found to sync.');
            return 0;
        }

        $this->info("Found {$webSubscriptions->count()} Web subscription(s) to check.");

        $merchantAuthentication = new AnetAPI\MerchantAuthenticationType();
        $merchantAuthentication->setName(env('AUTHORIZENET_API_LOGIN_ID'));
        $merchantAuthentication->setTransactionKey(env('AUTHORIZENET_TRANSACTION_KEY'));

        $updatedCount = 0;
        $errorCount = 0;
        $cancelledCount = 0;
        $alreadyUpToDateCount = 0;

        foreach ($webSubscriptions as $subscription) {
            try {
                // Get subscription status from Authorize.Net
                $request = new AnetAPI\ARBGetSubscriptionStatusRequest();
                $request->setMerchantAuthentication($merchantAuthentication);
                $request->setSubscriptionId($subscription->transaction_id);

                $controller = new AnetController\ARBGetSubscriptionStatusController($request);
                $response = $controller->executeWithApiResponse(\net\authorize\api\constants\ANetEnvironment::PRODUCTION);

                if ($response && $response->getMessages()->getResultCode() === "Ok") {
                    $anetStatus = strtolower($response->getStatus());
                    
                    // Handle different Authorize.Net subscription statuses
                    if ($anetStatus === 'active') {
                        // Active: Processing normally - calculate and update renewal date
                        $startDate = Carbon::parse($subscription->start_date);
                        $now = Carbon::now();
                        
                        // Calculate next billing date based on subscription type
                        $nextBillingDate = Carbon::parse($subscription->start_date);
                        $subscriptionType = strtolower($subscription->subscription_type ?? '');
                        
                        if (strpos($subscriptionType, 'monthly') !== false || strpos($subscriptionType, 'month') !== false) {
                            // Monthly - find next billing date
                            while ($nextBillingDate->lte($now)) {
                                $nextBillingDate->addMonth();
                            }
                        } else {
                            // Yearly - find next billing date
                            while ($nextBillingDate->lte($now)) {
                                $nextBillingDate->addYear();
                            }
                        }
                        
                        // Only update if the date is different
                        if ($subscription->renewal_date != $nextBillingDate->format('Y-m-d')) {
                            $oldDate = $subscription->renewal_date;
                            $subscription->renewal_date = $nextBillingDate->format('Y-m-d');
                            $subscription->save();
                            
                            $updatedCount++;
                            $this->info("Updated subscription ID {$subscription->id} - Renewal date: {$oldDate} → {$subscription->renewal_date}");
                        } else {
                            $alreadyUpToDateCount++;
                            $this->line("Subscription ID {$subscription->id} - renewal date already up to date");
                        }
                    } elseif ($anetStatus === 'suspended') {
                        // Suspended: Payment issue, needs merchant action
                        // Mark as cancelled since service is not active
                        $subscription->status = 'cancelled';
                        $subscription->save();
                        $this->updateUserPaidStatus($subscription->user_id);
                        $cancelledCount++;
                        $this->warn("Subscription ID {$subscription->id} is SUSPENDED in Authorize.Net (payment issue) - marked as cancelled in DB");
                    } elseif ($anetStatus === 'terminated') {
                        // Terminated: Permanently stopped due to unresolved suspension
                        $subscription->status = 'cancelled';
                        $subscription->save();
                        $this->updateUserPaidStatus($subscription->user_id);
                        $cancelledCount++;
                        $this->warn("Subscription ID {$subscription->id} is TERMINATED in Authorize.Net (permanently stopped) - marked as cancelled in DB");
                    } elseif ($anetStatus === 'canceled' || $anetStatus === 'cancelled') {
                        // Canceled: Merchant-initiated cancellation
                        $subscription->status = 'cancelled';
                        $subscription->save();
                        $this->updateUserPaidStatus($subscription->user_id);
                        $cancelledCount++;
                        $this->info("Subscription ID {$subscription->id} is CANCELED in Authorize.Net (merchant-initiated) - marked as cancelled in DB");
                    } elseif ($anetStatus === 'expired') {
                        // Expired: Successfully completed its schedule
                        $subscription->status = 'cancelled';
                        $subscription->save();
                        $this->updateUserPaidStatus($subscription->user_id);
                        $cancelledCount++;
                        $this->info("Subscription ID {$subscription->id} is EXPIRED in Authorize.Net (schedule completed) - marked as cancelled in DB");
                    } else {
                        // Unknown status - mark as cancelled for safety
                        $subscription->status = 'cancelled';
                        $subscription->save();
                        $this->updateUserPaidStatus($subscription->user_id);
                        $cancelledCount++;
                        $this->warn("Subscription ID {$subscription->id} has unknown status '{$anetStatus}' in Authorize.Net - marked as cancelled in DB");
                    }
                } else {
                    $errorMessages = $response->getMessages()->getMessage();
                    $errorMessage = isset($errorMessages[0]) ? $errorMessages[0]->getText() : 'Unknown error';
                    $this->error("Failed to get subscription status for ID {$subscription->id}: {$errorMessage}");
                    $errorCount++;
                }
            } catch (\Exception $e) {
                $this->error("Error processing subscription ID {$subscription->id}: " . $e->getMessage());
                $errorCount++;
            }
        }

        $this->info("Sync completed. Updated: {$updatedCount}, Cancelled: {$cancelledCount}, Already up-to-date: {$alreadyUpToDateCount}, Errors: {$errorCount}");
        return 0;
    }

    /**
     * Update user's paid status based on active subscriptions
     * 
     * @param int $userId
     * @return void
     */
    private function updateUserPaidStatus(int $userId): void
    {
        $user = User::find($userId);
        if (!$user) {
            return;
        }

        // Check if user has any active subscriptions (from any platform)
        $hasActiveSubscription = Subscription::where('user_id', $userId)
            ->where('status', 'active')
            ->exists();

        // Update paid status based on active subscription existence
        if ($hasActiveSubscription) {
            if ($user->paid !== 'Yes') {
                $user->update(['paid' => 'Yes']);
                $this->line("User ID {$userId} - updated paid status to 'Yes' (has active subscription)");
            }
        } else {
            if ($user->paid !== 'No') {
                $user->update(['paid' => 'No']);
                $this->line("User ID {$userId} - updated paid status to 'No' (no active subscriptions)");
            }
        }
    }
}

