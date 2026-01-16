<?php

namespace App\Console\Commands;

use App\Models\Business\Subscription;
use App\Models\SubscriptionBilling;
use App\Services\GooglePlayService;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use net\authorize\api\contract\v1 as AnetAPI;
use net\authorize\api\controller as AnetController;

class SyncSubscriptionBillingHistory extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'subscriptions:sync-billing-history';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync historical subscription lifecycle events (created, renewed, cancelled, expired, etc.) for all platforms';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting to sync billing history for all platforms...');

        // Sync Web/Authorize.Net subscriptions
        $this->info('=== Syncing Web/Authorize.Net Subscriptions ===');
        $webResult = $this->syncAuthorizeNetBillingHistory();

        // Sync Google Play subscriptions
        $this->info('=== Syncing Google Play Subscriptions ===');
        $googleResult = $this->syncGooglePlayBillingHistory();

        // Sync Apple subscriptions
        $this->info('=== Syncing Apple Subscriptions ===');
        $appleResult = $this->syncAppleBillingHistory();

        $this->info('=== Sync Summary ===');
        $this->info("Web/Authorize.Net: Updated: {$webResult['updated']}, Billing Records: {$webResult['billing_records']}, Errors: {$webResult['errors']}");
        $this->info("Google Play: Updated: {$googleResult['updated']}, Billing Records: {$googleResult['billing_records']}, Errors: {$googleResult['errors']}");
        $this->info("Apple: Updated: {$appleResult['updated']}, Billing Records: {$appleResult['billing_records']}, Errors: {$appleResult['errors']}");

        return 0;
    }

    /**
     * Sync Authorize.Net (Web platform) billing history
     */
    private function syncAuthorizeNetBillingHistory(): array
    {
        $subscriptions = Subscription::with('user')
            ->where('platform', 'Web')
            ->whereNotNull('transaction_id')
            ->get();

        if ($subscriptions->isEmpty()) {
            $this->info('No Web platform subscriptions found to sync.');
            return ['updated' => 0, 'billing_records' => 0, 'errors' => 0];
        }

        $this->info("Found {$subscriptions->count()} Web subscription(s) to check.");

        $merchantAuthentication = new AnetAPI\MerchantAuthenticationType();
        $merchantAuthentication->setName(env('AUTHORIZENET_API_LOGIN_ID'));
        $merchantAuthentication->setTransactionKey(env('AUTHORIZENET_TRANSACTION_KEY'));

        $updatedCount = 0;
        $errorCount = 0;
        $billingHistoryCount = 0;

        foreach ($subscriptions as $subscription) {
            try {
                $detailRequest = new AnetAPI\ARBGetSubscriptionRequest();
                $detailRequest->setMerchantAuthentication($merchantAuthentication);
                $detailRequest->setSubscriptionId($subscription->transaction_id);

                $detailController = new AnetController\ARBGetSubscriptionController($detailRequest);
                $detailResponse = $detailController->executeWithApiResponse(\net\authorize\api\constants\ANetEnvironment::PRODUCTION);

                if ($detailResponse && $detailResponse->getMessages()->getResultCode() === "Ok") {
                    $subscriptionDetails = $detailResponse->getSubscription();
                    $paymentSchedule = $subscriptionDetails->getPaymentSchedule();
                    $result = $this->calculateAndSaveBillingHistory($subscription, $paymentSchedule, 'Synced from Authorize.Net payment schedule');
                    
                    $updatedCount += $result['updated'];
                    $billingHistoryCount += $result['billing_records'];
                } else {
                    $errorMessages = $detailResponse->getMessages()->getMessage();
                    $errorMessage = isset($errorMessages[0]) ? $errorMessages[0]->getText() : 'Unknown error';
                    $this->error("Failed to get subscription details for ID {$subscription->id}: {$errorMessage}");
                    $errorCount++;
                }
            } catch (\Exception $e) {
                $this->error("Error processing subscription ID {$subscription->id}: " . $e->getMessage());
                Log::error("Authorize.Net billing history sync error for subscription {$subscription->id}: " . $e->getMessage());
                $errorCount++;
            }
        }

        return ['updated' => $updatedCount, 'billing_records' => $billingHistoryCount, 'errors' => $errorCount];
    }

    /**
     * Sync Google Play billing history
     */
    private function syncGooglePlayBillingHistory(): array
    {
        $subscriptions = Subscription::with('user')
            ->where('platform', 'google')
            ->whereNotNull('transaction_id')
            ->get();

        if ($subscriptions->isEmpty()) {
            $this->info('No Google Play subscriptions found to sync.');
            return ['updated' => 0, 'billing_records' => 0, 'errors' => 0];
        }

        $this->info("Found {$subscriptions->count()} Google Play subscription(s) to check.");

        try {
            $googlePlay = app(GooglePlayService::class);
        } catch (\Exception $e) {
            $this->error("Failed to initialize Google Play service: " . $e->getMessage());
            return ['updated' => 0, 'billing_records' => 0, 'errors' => $subscriptions->count()];
        }

        $updatedCount = 0;
        $errorCount = 0;
        $billingHistoryCount = 0;

        foreach ($subscriptions as $subscription) {
            try {
                $planType = $subscription->subscription_type === 'Monthly' ? 'Premium_Monthly' : 'Premium_Yearly';
                $productId = config("services.google_play.products.{$planType}");

                if (!$productId) {
                    $this->warn("Subscription ID {$subscription->id} - Product ID not configured for type: {$planType}");
                    $errorCount++;
                    continue;
                }

                $purchaseToken = $subscription->transaction_id;
                $receiptData = $subscription->receipt_data ? json_decode($subscription->receipt_data, true) : [];
                if (isset($receiptData['purchaseToken'])) {
                    $purchaseToken = $receiptData['purchaseToken'];
                }

                if (!$purchaseToken) {
                    $this->warn("Subscription ID {$subscription->id} - No purchase token found");
                    $errorCount++;
                    continue;
                }

                try {
                    $purchase = $googlePlay->getSubscriptionPurchase($productId, $purchaseToken);
                } catch (\Google\Service\Exception $e) {
                    // Handle expired subscriptions gracefully
                    $errorDetails = json_decode($e->getMessage(), true);
                    if (isset($errorDetails['error']['code']) && $errorDetails['error']['code'] == 410) {
                        $this->warn("Subscription ID {$subscription->id} - Subscription expired too long ago, cannot fetch details. Using database start_date for billing calculation.");
                        // Fall through to calculate from start_date only
                        $purchase = null;
                    } else {
                        throw $e;
                    }
                }

                // Get purchase details if available, otherwise use defaults
                $expiryTimeMillis = $purchase ? $purchase->getExpiryTimeMillis() : null;
                $expiryDate = $expiryTimeMillis ? Carbon::createFromTimestampMs($expiryTimeMillis) : null;
                $paymentState = $purchase ? (int) $purchase->getPaymentState() : null;
                $autoRenewing = $purchase ? (bool) $purchase->getAutoRenewing() : false;
                $cancelReason = $purchase ? $purchase->getCancelReason() : null;

                // Use subscription start_date from database (Google Play API doesn't provide initiation timestamp)
                $startDate = $subscription->start_date ? Carbon::parse($subscription->start_date) : null;
                
                if (!$startDate) {
                    $this->warn("Subscription ID {$subscription->id} - No start_date found, skipping billing history calculation");
                    continue;
                }

                $currentDate = Carbon::now();
                $intervalLength = $subscription->subscription_type === 'Monthly' ? 1 : 12;
                $intervalUnit = 'months';

                // Calculate billing dates from start date
                $billingDates = [];
                $nextBilling = clone $startDate;

                while ($nextBilling->lte($currentDate)) {
                    $billingDates[] = clone $nextBilling;
                    $nextBilling->addMonths($intervalLength);
                    
                    // Safety check to prevent infinite loop
                    if (count($billingDates) > 120) { // Max 10 years for monthly, ~10 years for yearly
                        break;
                    }
                }

                // Record "created" event (first billing date)
                if (count($billingDates) > 0) {
                    $createdDate = $billingDates[0];
                    SubscriptionBilling::createEvent([
                        'subscription_id' => $subscription->id,
                        'user_id' => $subscription->user_id,
                        'platform' => 'google',
                        'event_type' => SubscriptionBilling::EVENT_CREATED,
                        'event_date' => $createdDate->format('Y-m-d'),
                        'billing_date' => $createdDate->format('Y-m-d'),
                        'amount' => $subscription->subscription_amount ?? 0,
                        'transaction_id' => $purchaseToken,
                        'status' => 'success',
                        'notes' => 'Subscription created - synced from Google Play',
                    ]);
                    $billingHistoryCount++;
                }

                // Record "renewed" events (all subsequent billings)
                foreach (array_slice($billingDates, 1) as $billingDate) {
                    SubscriptionBilling::createEvent([
                        'subscription_id' => $subscription->id,
                        'user_id' => $subscription->user_id,
                        'platform' => 'google',
                        'event_type' => SubscriptionBilling::EVENT_RENEWED,
                        'event_date' => $billingDate->format('Y-m-d'),
                        'billing_date' => $billingDate->format('Y-m-d'),
                        'amount' => $subscription->subscription_amount ?? 0,
                        'transaction_id' => $purchaseToken,
                        'status' => 'success',
                        'notes' => 'Subscription renewed - synced from Google Play',
                    ]);
                    $billingHistoryCount++;
                }

                // Update renewal count
                $renewalCount = max(0, count($billingDates) - 1);
                if ($subscription->renewal_count !== $renewalCount) {
                    $subscription->renewal_count = $renewalCount;
                    if (count($billingDates) > 1) {
                        $subscription->last_renewed_at = end($billingDates);
                    }
                    $subscription->save();
                    $updatedCount++;
                }

                // Record "cancelled" or "expired" event if not active (only if we have purchase data)
                if ($purchase) {
                    $isActive = $expiryTimeMillis && $expiryDate && $expiryDate->isFuture() && $paymentState === 1 && $autoRenewing;

                    if (!$isActive || $cancelReason || ($expiryDate && $expiryDate->isPast())) {
                        $eventDate = $expiryDate && $expiryDate->isPast() ? $expiryDate : Carbon::now();
                        SubscriptionBilling::createEvent([
                            'subscription_id' => $subscription->id,
                            'user_id' => $subscription->user_id,
                            'platform' => 'google',
                            'event_type' => $cancelReason ? SubscriptionBilling::EVENT_CANCELLED : SubscriptionBilling::EVENT_EXPIRED,
                            'event_date' => $eventDate->format('Y-m-d'),
                            'status_from' => 'active',
                            'status_to' => $cancelReason ? 'cancelled' : 'expired',
                            'notes' => $cancelReason ? "Subscription cancelled - Reason: {$cancelReason}" : 'Subscription expired',
                            'metadata' => [
                                'payment_state' => $paymentState,
                                'auto_renewing' => $autoRenewing,
                                'cancel_reason' => $cancelReason,
                                'expiry_date' => $expiryDate ? $expiryDate->toIso8601String() : null,
                            ],
                        ]);
                        $billingHistoryCount++;
                    }
                }
            } catch (\Exception $e) {
                $this->error("Error processing subscription ID {$subscription->id}: " . $e->getMessage());
                Log::error("Google Play billing history sync error for subscription {$subscription->id}: " . $e->getMessage());
                $errorCount++;
            }
        }

        return ['updated' => $updatedCount, 'billing_records' => $billingHistoryCount, 'errors' => $errorCount];
    }

    /**
     * Sync Apple billing history
     */
    private function syncAppleBillingHistory(): array
    {
        $subscriptions = Subscription::with('user')
            ->where('platform', 'apple')
            ->whereNotNull('transaction_id')
            ->get();

        if ($subscriptions->isEmpty()) {
            $this->info('No Apple subscriptions found to sync.');
            return ['updated' => 0, 'billing_records' => 0, 'errors' => 0];
        }

        $this->info("Found {$subscriptions->count()} Apple subscription(s) to check.");

        $updatedCount = 0;
        $errorCount = 0;
        $billingHistoryCount = 0;

        foreach ($subscriptions as $subscription) {
            try {
                // Calculate billing dates from start_date (Apple doesn't provide detailed history easily)
                if (!$subscription->start_date) {
                    $this->warn("Subscription ID {$subscription->id} - No start_date found");
                    $errorCount++;
                    continue;
                }

                $startDate = Carbon::parse($subscription->start_date);
                $currentDate = Carbon::now();
                $intervalLength = $subscription->subscription_type === 'Monthly' ? 1 : 12;
                $intervalUnit = 'months';

                // Calculate billing dates
                $billingDates = [];
                $nextBilling = clone $startDate;

                while ($nextBilling->lte($currentDate)) {
                    $billingDates[] = clone $nextBilling;
                    $nextBilling->addMonths($intervalLength);
                    
                    // Safety check to prevent infinite loop
                    if (count($billingDates) > 120) { // Max 10 years
                        break;
                    }
                }

                // Record "created" event (first billing date)
                if (count($billingDates) > 0) {
                    $createdDate = $billingDates[0];
                    SubscriptionBilling::createEvent([
                        'subscription_id' => $subscription->id,
                        'user_id' => $subscription->user_id,
                        'platform' => 'apple',
                        'event_type' => SubscriptionBilling::EVENT_CREATED,
                        'event_date' => $createdDate->format('Y-m-d'),
                        'billing_date' => $createdDate->format('Y-m-d'),
                        'amount' => $subscription->subscription_amount ?? 0,
                        'transaction_id' => $subscription->transaction_id,
                        'status' => 'success',
                        'notes' => 'Subscription created - synced from Apple (calculated from start_date)',
                    ]);
                    $billingHistoryCount++;
                }

                // Record "renewed" events (all subsequent billings)
                foreach (array_slice($billingDates, 1) as $billingDate) {
                    SubscriptionBilling::createEvent([
                        'subscription_id' => $subscription->id,
                        'user_id' => $subscription->user_id,
                        'platform' => 'apple',
                        'event_type' => SubscriptionBilling::EVENT_RENEWED,
                        'event_date' => $billingDate->format('Y-m-d'),
                        'billing_date' => $billingDate->format('Y-m-d'),
                        'amount' => $subscription->subscription_amount ?? 0,
                        'transaction_id' => $subscription->transaction_id,
                        'status' => 'success',
                        'notes' => 'Subscription renewed - synced from Apple (calculated from start_date)',
                    ]);
                    $billingHistoryCount++;
                }

                // Record "cancelled" event if cancelled
                if ($subscription->status === 'cancelled' && $subscription->cancelled_at) {
                    $cancelledAt = is_string($subscription->cancelled_at) ? Carbon::parse($subscription->cancelled_at) : $subscription->cancelled_at;
                    SubscriptionBilling::createEvent([
                        'subscription_id' => $subscription->id,
                        'user_id' => $subscription->user_id,
                        'platform' => 'apple',
                        'event_type' => SubscriptionBilling::EVENT_CANCELLED,
                        'event_date' => $cancelledAt->format('Y-m-d'),
                        'status_from' => 'active',
                        'status_to' => 'cancelled',
                        'notes' => 'Subscription cancelled - synced from Apple',
                        'metadata' => ['cancelled_at' => $cancelledAt->toIso8601String()],
                    ]);
                    $billingHistoryCount++;
                }

                // Record "expired" event if expired (check expires_at)
                if ($subscription->expires_at) {
                    $expiresAt = is_string($subscription->expires_at) ? Carbon::parse($subscription->expires_at) : $subscription->expires_at;
                    if ($expiresAt->isPast() && $subscription->status === 'active') {
                        SubscriptionBilling::createEvent([
                            'subscription_id' => $subscription->id,
                            'user_id' => $subscription->user_id,
                            'platform' => 'apple',
                            'event_type' => SubscriptionBilling::EVENT_EXPIRED,
                            'event_date' => $expiresAt->format('Y-m-d'),
                            'status_from' => 'active',
                            'status_to' => 'expired',
                            'notes' => 'Subscription expired - synced from Apple',
                            'metadata' => ['expires_at' => $expiresAt->toIso8601String()],
                        ]);
                        $billingHistoryCount++;
                    }
                }

                // Update renewal count
                $renewalCount = max(0, count($billingDates) - 1);
                if ($subscription->renewal_count !== $renewalCount) {
                    $subscription->renewal_count = $renewalCount;
                    if (count($billingDates) > 1) {
                        $subscription->last_renewed_at = end($billingDates);
                    }
                    $subscription->save();
                    $updatedCount++;
                }
            } catch (\Exception $e) {
                $this->error("Error processing subscription ID {$subscription->id}: " . $e->getMessage());
                Log::error("Apple billing history sync error for subscription {$subscription->id}: " . $e->getMessage());
                $errorCount++;
            }
        }

        return ['updated' => $updatedCount, 'billing_records' => $billingHistoryCount, 'errors' => $errorCount];
    }

    /**
     * Calculate and save billing history based on payment schedule (for Authorize.Net)
     */
    private function calculateAndSaveBillingHistory(Subscription $subscription, $paymentSchedule, string $notes): array
    {
        $renewalCount = 0;
        $lastRenewedAt = null;
        $billingAmount = $subscription->subscription_amount ?? 0;
        $billingHistoryCount = 0;
        $needsSave = false;

        if ($paymentSchedule) {
            $startDate = $paymentSchedule->getStartDate();
            $interval = $paymentSchedule->getInterval();
            $totalOccurrences = $paymentSchedule->getTotalOccurrences();
            $trialOccurrences = $paymentSchedule->getTrialOccurrences() ?? 0;

            if ($startDate && $interval) {
                $startCarbon = Carbon::parse($startDate);
                $intervalLength = $interval->getLength();
                $intervalUnit = $interval->getUnit();
                $currentDate = Carbon::now();

                if ($intervalLength && $intervalUnit) {
                    $billingDates = [];
                    $nextBilling = clone $startCarbon;

                    // Skip trial periods
                    if ($trialOccurrences > 0) {
                        for ($i = 0; $i < $trialOccurrences; $i++) {
                            if ($intervalUnit === 'months') {
                                $nextBilling->addMonths($intervalLength);
                            } elseif ($intervalUnit === 'days') {
                                $nextBilling->addDays($intervalLength);
                            }
                        }
                    }

                    // Generate all paid billing dates up to now
                    $maxPaidOccurrences = $totalOccurrences ? ($totalOccurrences - $trialOccurrences) : null;
                    $iteration = 0;

                    while ($nextBilling->lte($currentDate) && ($maxPaidOccurrences === null || $iteration < $maxPaidOccurrences)) {
                        $billingDates[] = clone $nextBilling;
                        $iteration++;

                        if ($intervalUnit === 'months') {
                            $nextBilling->addMonths($intervalLength);
                        } elseif ($intervalUnit === 'days') {
                            $nextBilling->addDays($intervalLength);
                        } else {
                            break;
                        }
                    }

                    // Record "created" event (first billing date)
                    if (count($billingDates) > 0) {
                        $createdDate = $billingDates[0];
                        SubscriptionBilling::createEvent([
                            'subscription_id' => $subscription->id,
                            'user_id' => $subscription->user_id,
                            'platform' => $subscription->platform ?? 'Web',
                            'event_type' => SubscriptionBilling::EVENT_CREATED,
                            'event_date' => $createdDate->format('Y-m-d'),
                            'billing_date' => $createdDate->format('Y-m-d'),
                            'amount' => $billingAmount,
                            'transaction_id' => $subscription->transaction_id,
                            'status' => 'success',
                            'notes' => $notes . ' - Subscription created',
                        ]);
                        $billingHistoryCount++;
                    }

                    // Record "renewed" events (all subsequent billings)
                    foreach (array_slice($billingDates, 1) as $billingDate) {
                        SubscriptionBilling::createEvent([
                            'subscription_id' => $subscription->id,
                            'user_id' => $subscription->user_id,
                            'platform' => $subscription->platform ?? 'Web',
                            'event_type' => SubscriptionBilling::EVENT_RENEWED,
                            'event_date' => $billingDate->format('Y-m-d'),
                            'billing_date' => $billingDate->format('Y-m-d'),
                            'amount' => $billingAmount,
                            'transaction_id' => $subscription->transaction_id,
                            'status' => 'success',
                            'notes' => $notes . ' - Subscription renewed',
                        ]);
                        $billingHistoryCount++;
                    }

                    // Record "cancelled" event if cancelled
                    if ($subscription->status === 'cancelled' && $subscription->cancelled_at) {
                        $cancelledAt = is_string($subscription->cancelled_at) ? Carbon::parse($subscription->cancelled_at) : $subscription->cancelled_at;
                        SubscriptionBilling::createEvent([
                            'subscription_id' => $subscription->id,
                            'user_id' => $subscription->user_id,
                            'platform' => $subscription->platform ?? 'Web',
                            'event_type' => SubscriptionBilling::EVENT_CANCELLED,
                            'event_date' => $cancelledAt->format('Y-m-d'),
                            'status_from' => 'active',
                            'status_to' => 'cancelled',
                            'notes' => 'Subscription cancelled - synced from Authorize.Net',
                            'metadata' => ['cancelled_at' => $cancelledAt->toIso8601String()],
                        ]);
                        $billingHistoryCount++;
                    }

                    // Record "expired" event if expired
                    if ($subscription->expires_at) {
                        $expiresAt = is_string($subscription->expires_at) ? Carbon::parse($subscription->expires_at) : $subscription->expires_at;
                        if ($expiresAt->isPast() && $subscription->status === 'active') {
                            SubscriptionBilling::createEvent([
                                'subscription_id' => $subscription->id,
                                'user_id' => $subscription->user_id,
                                'platform' => $subscription->platform ?? 'Web',
                                'event_type' => SubscriptionBilling::EVENT_EXPIRED,
                                'event_date' => $expiresAt->format('Y-m-d'),
                                'status_from' => 'active',
                                'status_to' => 'expired',
                                'notes' => 'Subscription expired - synced from Authorize.Net',
                                'metadata' => ['expires_at' => $expiresAt->toIso8601String()],
                            ]);
                            $billingHistoryCount++;
                        }
                    }

                    // Calculate renewal count
                    $renewalCount = max(0, count($billingDates) - 1);
                    if (count($billingDates) > 1) {
                        $lastRenewedAt = $billingDates[count($billingDates) - 1];
                    }
                }
            }
        }

        // Update subscription with renewal history
        if ($subscription->renewal_count !== $renewalCount) {
            $subscription->renewal_count = $renewalCount;
            $needsSave = true;
        }

        if ($lastRenewedAt && $subscription->last_renewed_at != $lastRenewedAt->format('Y-m-d H:i:s')) {
            $subscription->last_renewed_at = $lastRenewedAt;
            $needsSave = true;
        }

        if ($needsSave) {
            $subscription->save();
            return ['updated' => 1, 'billing_records' => $billingHistoryCount];
        }

        return ['updated' => 0, 'billing_records' => $billingHistoryCount];
    }
}
