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
                // Check if user exists (handle soft-deleted users)
                if (!$subscription->user_id || !\App\Models\User::where('id', $subscription->user_id)->exists()) {
                    $this->warn("Subscription ID {$subscription->id} - User ID {$subscription->user_id} does not exist (likely soft-deleted), skipping");
                    $errorCount++;
                    continue;
                }

                // Get subscription details from Authorize.Net
                // NOTE: For complete historical transaction data with actual amounts per transaction,
                // you would need to use Transaction Reporting API (getTransactionListRequest)
                // to query transactions by subscription ID. The ARB API only provides schedule info,
                // not individual transaction history with varying amounts.
                // For now, we use the subscription amount and calculate dates from payment schedule.
                
                $detailRequest = new AnetAPI\ARBGetSubscriptionRequest();
                $detailRequest->setMerchantAuthentication($merchantAuthentication);
                $detailRequest->setSubscriptionId($subscription->transaction_id);

                $detailController = new AnetController\ARBGetSubscriptionController($detailRequest);
                $detailResponse = $detailController->executeWithApiResponse(\net\authorize\api\constants\ANetEnvironment::PRODUCTION);

                if ($detailResponse && $detailResponse->getMessages()->getResultCode() === "Ok") {
                    $subscriptionDetails = $detailResponse->getSubscription();
                    $paymentSchedule = $subscriptionDetails->getPaymentSchedule();
                    
                    // Get amount from Authorize.Net subscription details (platform source)
                    $anetAmount = null;
                    $anetStatus = null;
                    try {
                        // Authorize.Net subscription object has getAmount() method
                        if (method_exists($subscriptionDetails, 'getAmount')) {
                            $anetAmount = $subscriptionDetails->getAmount();
                        }
                        // Get status from Authorize.Net
                        if (method_exists($subscriptionDetails, 'getStatus')) {
                            $anetStatus = strtolower($subscriptionDetails->getStatus());
                        }
                    } catch (\Exception $e) {
                        Log::debug("Could not get amount/status from Authorize.Net for subscription {$subscription->id}: " . $e->getMessage());
                    }
                    
                    // Use Authorize.Net amount and status (platform source), fallback to database if not available
                    $billingAmount = $anetAmount ?? ($subscription->subscription_amount ?? 0);
                    $platformStatus = $anetStatus ?? strtolower($subscription->status ?? 'unknown');
                    
                    $result = $this->calculateAndSaveBillingHistory($subscription, $paymentSchedule, 'Synced from Authorize.Net payment schedule', $billingAmount, $platformStatus);
                    
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
                // Check if user exists (handle soft-deleted users)
                if (!$subscription->user_id || !\App\Models\User::where('id', $subscription->user_id)->exists()) {
                    $this->warn("Subscription ID {$subscription->id} - User ID {$subscription->user_id} does not exist (likely soft-deleted), skipping");
                    $errorCount++;
                    continue;
                }

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

                // Get purchase details from Google Play (platform source)
                $expiryTimeMillis = $purchase ? $purchase->getExpiryTimeMillis() : null;
                $expiryDate = $expiryTimeMillis ? Carbon::createFromTimestampMs($expiryTimeMillis) : null;
                $paymentState = $purchase ? (int) $purchase->getPaymentState() : null;
                $autoRenewing = $purchase ? (bool) $purchase->getAutoRenewing() : false;
                $cancelReason = $purchase ? $purchase->getCancelReason() : null;
                
                // Try to get price from Google Play purchase - amount is typically in orderId metadata or product pricing
                // Note: Google Play API doesn't directly provide amount in subscription purchase object
                // We can get it from the product pricing if available, otherwise use database amount
                $googleAmount = null;
                try {
                    // Check if purchase has price information
                    if ($purchase && method_exists($purchase, 'getPriceCurrencyCode')) {
                        // Price might be in receipt_data or we'd need to query Google Play Product API
                        // For now, use database amount as fallback since amount isn't in subscription purchase response
                    }
                } catch (\Exception $e) {
                    Log::debug("Could not get amount from Google Play for subscription {$subscription->id}: " . $e->getMessage());
                }
                
                // Use database amount for Google Play (amount not available in subscription purchase API response)
                $billingAmount = $subscription->subscription_amount ?? 0;

                // IMPORTANT LIMITATION: Google Play Developer API does NOT provide historical transaction/renewal history
                // There is NO API endpoint that returns past renewal transactions with dates and amounts
                // We can only get the current subscription state, not historical renewals
                // Therefore, we must calculate billing dates from start_date (not ideal, but only option)
                // Note: For accurate historical data, you would need to log each renewal via Real-Time Developer Notifications (RTDN) as it happens
                
                $startDate = $subscription->start_date ? Carbon::parse($subscription->start_date) : null;
                
                if (!$startDate) {
                    $this->warn("Subscription ID {$subscription->id} - No start_date found, skipping billing history calculation");
                    continue;
                }

                $currentDate = Carbon::now();
                $intervalLength = $subscription->subscription_type === 'Monthly' ? 1 : 12;
                $intervalUnit = 'months';

                // Calculate billing dates from start date (Google Play doesn't provide historical transactions)
                // WARNING: These are calculated dates, not actual transaction dates from Google Play
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

                // Determine status from Google Play payment state (platform source)
                $platformStatus = 'success';
                if ($paymentState !== null) {
                    // Payment state: 0=Pending, 1=Free trial, 2=Active, 3=Grace period, 4=On hold
                    $platformStatus = match($paymentState) {
                        0 => 'pending',
                        1 => 'trial',
                        2 => 'success',
                        3 => 'grace_period',
                        4 => 'on_hold',
                        default => 'success'
                    };
                }

                // Record "created" event (first billing date)
                if (count($billingDates) > 0) {
                    $createdDate = $billingDates[0];
                    $result = SubscriptionBilling::createEvent([
                        'subscription_id' => $subscription->id,
                        'user_id' => $subscription->user_id,
                        'platform' => 'google',
                        'event_type' => SubscriptionBilling::EVENT_CREATED,
                        'event_date' => $createdDate->format('Y-m-d'),
                        'billing_date' => $createdDate->format('Y-m-d'),
                        'amount' => $billingAmount,
                        'transaction_id' => $purchaseToken,
                        'status' => $platformStatus,
                        'notes' => 'Subscription created - synced from Google Play (calculated date - Google Play API does not provide historical transactions)',
                    ]);
                    if ($result['wasCreated']) {
                        $billingHistoryCount++;
                    }
                }

                // Record "renewed" events (all subsequent billings)
                foreach (array_slice($billingDates, 1) as $billingDate) {
                    $result = SubscriptionBilling::createEvent([
                        'subscription_id' => $subscription->id,
                        'user_id' => $subscription->user_id,
                        'platform' => 'google',
                        'event_type' => SubscriptionBilling::EVENT_RENEWED,
                        'event_date' => $billingDate->format('Y-m-d'),
                        'billing_date' => $billingDate->format('Y-m-d'),
                        'amount' => $billingAmount,
                        'transaction_id' => $purchaseToken,
                        'status' => $platformStatus,
                        'notes' => 'Subscription renewed - synced from Google Play (calculated date - Google Play API does not provide historical transactions)',
                    ]);
                    if ($result['wasCreated']) {
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

                // Record "cancelled" or "expired" event if not active (only if we have purchase data from Google Play)
                if ($purchase) {
                    $isActive = $expiryTimeMillis && $expiryDate && $expiryDate->isFuture() && $paymentState === 1 && $autoRenewing;

                    if (!$isActive || $cancelReason || ($expiryDate && $expiryDate->isPast())) {
                        $eventDate = $expiryDate && $expiryDate->isPast() ? $expiryDate : Carbon::now();
                        // Map Google Play payment state to status
                        $eventStatus = match($paymentState) {
                            0 => 'pending',
                            1 => 'success', // Free trial (not paid yet)
                            2 => 'success', // Active (paid)
                            default => $platformStatus
                        };
                        
                        $result = SubscriptionBilling::createEvent([
                            'subscription_id' => $subscription->id,
                            'user_id' => $subscription->user_id,
                            'platform' => 'google',
                            'event_type' => $cancelReason ? SubscriptionBilling::EVENT_CANCELLED : SubscriptionBilling::EVENT_EXPIRED,
                            'event_date' => $eventDate->format('Y-m-d'),
                            'status_from' => 'active',
                            'status_to' => $cancelReason ? 'cancelled' : 'expired',
                            'status' => $eventStatus,
                            'notes' => $cancelReason ? "Subscription cancelled - Reason: {$cancelReason} (Google Play)" : 'Subscription expired - synced from Google Play',
                            'metadata' => [
                                'payment_state' => $paymentState,
                                'auto_renewing' => $autoRenewing,
                                'cancel_reason' => $cancelReason,
                                'expiry_date' => $expiryDate ? $expiryDate->toIso8601String() : null,
                                'platform_status' => $platformStatus,
                            ],
                        ]);
                        if ($result['wasCreated']) {
                            $billingHistoryCount++;
                        }
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
                // Check if user exists (handle soft-deleted users)
                if (!$subscription->user_id || !\App\Models\User::where('id', $subscription->user_id)->exists()) {
                    $this->warn("Subscription ID {$subscription->id} - User ID {$subscription->user_id} does not exist (likely soft-deleted), skipping");
                    $errorCount++;
                    continue;
                }

                // Parse Apple receipt data to get ACTUAL transaction history from latest_receipt_info
                $receiptData = $subscription->receipt_data ? json_decode($subscription->receipt_data, true) : [];
                
                if (!is_array($receiptData) || empty($receiptData['latest_receipt_info'])) {
                    $this->warn("Subscription ID {$subscription->id} - No receipt_data or latest_receipt_info found, skipping");
                    $errorCount++;
                    continue;
                }

                $transactions = $receiptData['latest_receipt_info'];
                if (!is_array($transactions) || empty($transactions)) {
                    $this->warn("Subscription ID {$subscription->id} - latest_receipt_info is empty, skipping");
                    $errorCount++;
                    continue;
                }

                // Sort transactions by purchase_date_ms (oldest first)
                usort($transactions, function($a, $b) {
                    $dateA = isset($a['purchase_date_ms']) ? (int)$a['purchase_date_ms'] : 0;
                    $dateB = isset($b['purchase_date_ms']) ? (int)$b['purchase_date_ms'] : 0;
                    return $dateA <=> $dateB;
                });

                // Determine status from Apple subscription status (platform source)
                // Apple status: 1=Active, 2=Expired, 3=In Billing Retry Period, 4=In Grace Period, 5=Revoked
                $platformStatus = 'success';
                if (isset($receiptData['status'])) {
                    $appleStatus = (int) $receiptData['status'];
                    $platformStatus = match($appleStatus) {
                        1 => 'success', // Active
                        2 => 'expired',
                        3 => 'retry',
                        4 => 'grace_period',
                        5 => 'revoked',
                        default => 'success'
                    };
                }

                // Process each ACTUAL transaction from Apple receipt data
                $originalTransactionId = $subscription->transaction_id;
                $isFirstTransaction = true;

                foreach ($transactions as $txn) {
                    // Get transaction date from purchase_date_ms (milliseconds) or purchase_date
                    $purchaseDate = null;
                    if (isset($txn['purchase_date_ms'])) {
                        $purchaseDate = Carbon::createFromTimestampMs((int)$txn['purchase_date_ms']);
                    } elseif (isset($txn['purchase_date'])) {
                        try {
                            $purchaseDate = Carbon::parse($txn['purchase_date']);
                        } catch (\Exception $e) {
                            Log::warning("Invalid purchase_date format for subscription {$subscription->id}: " . $txn['purchase_date']);
                            continue;
                        }
                    }

                    if (!$purchaseDate) {
                        $this->warn("Subscription ID {$subscription->id} - Transaction missing purchase_date, skipping");
                        continue;
                    }

                    // Get transaction ID from receipt data
                    $txnId = $txn['transaction_id'] ?? $subscription->transaction_id;
                    
                    // Get amount - Apple receipts don't include price, so use subscription_amount from DB
                    // If you have stored different amounts per transaction, check receipt_data for custom fields
                    $txnAmount = $subscription->subscription_amount ?? 0;
                    
                    // Check if this is a trial period transaction (shouldn't count as billing)
                    $isTrial = isset($txn['is_trial_period']) && strtolower($txn['is_trial_period']) === 'true';
                    
                    if ($isTrial) {
                        // Skip trial periods - don't create billing records for free trials
                        continue;
                    }

                    // Determine if this is the original purchase or a renewal
                    $originalTxnId = $txn['original_transaction_id'] ?? null;
                    $isOriginal = ($originalTxnId && $txnId === $originalTxnId) || $isFirstTransaction;

                    $eventType = $isOriginal ? SubscriptionBilling::EVENT_CREATED : SubscriptionBilling::EVENT_RENEWED;

                    $result = SubscriptionBilling::createEvent([
                        'subscription_id' => $subscription->id,
                        'user_id' => $subscription->user_id,
                        'platform' => 'apple',
                        'event_type' => $eventType,
                        'event_date' => $purchaseDate->format('Y-m-d'),
                        'billing_date' => $purchaseDate->format('Y-m-d'),
                        'amount' => $txnAmount,
                        'transaction_id' => $txnId,
                        'status' => $platformStatus,
                        'notes' => $isOriginal 
                            ? "Subscription created - actual transaction from Apple receipt" 
                            : "Subscription renewed - actual transaction from Apple receipt",
                        'metadata' => [
                            'original_transaction_id' => $originalTxnId,
                            'expires_date_ms' => $txn['expires_date_ms'] ?? null,
                            'product_id' => $txn['product_id'] ?? null,
                        ],
                    ]);
                    
                    if ($result['wasCreated']) {
                        $billingHistoryCount++;
                    }

                    $isFirstTransaction = false;
                }

                // Record "cancelled" or "expired" event based on platform status
                // Check receipt data for cancellation/expiration status from Apple
                $isCancelled = false;
                $isExpired = false;
                if (is_array($receiptData)) {
                    if (isset($receiptData['status'])) {
                        $appleStatus = (int) $receiptData['status'];
                        $isExpired = $appleStatus === 2; // Expired
                        $isCancelled = $appleStatus === 5 || ($subscription->status === 'cancelled'); // Revoked or cancelled
                    }
                }

                if ($isCancelled && $subscription->cancelled_at) {
                    $cancelledAt = is_string($subscription->cancelled_at) ? Carbon::parse($subscription->cancelled_at) : $subscription->cancelled_at;
                    $result = SubscriptionBilling::createEvent([
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
                    if ($result['wasCreated']) {
                        $billingHistoryCount++;
                    }
                }

                // Record "expired" event if expired (check expires_at or Apple status)
                if ($isExpired || ($subscription->expires_at && ($expiresAt = is_string($subscription->expires_at) ? Carbon::parse($subscription->expires_at) : $subscription->expires_at) && $expiresAt->isPast())) {
                    $expiresDate = $subscription->expires_at ? (is_string($subscription->expires_at) ? Carbon::parse($subscription->expires_at) : $subscription->expires_at) : Carbon::now();
                    $result = SubscriptionBilling::createEvent([
                        'subscription_id' => $subscription->id,
                        'user_id' => $subscription->user_id,
                        'platform' => 'apple',
                        'event_type' => SubscriptionBilling::EVENT_EXPIRED,
                        'event_date' => $expiresDate->format('Y-m-d'),
                        'status_from' => 'active',
                        'status_to' => 'expired',
                        'notes' => 'Subscription expired - synced from Apple',
                        'metadata' => ['expires_at' => $expiresDate->toIso8601String()],
                    ]);
                    if ($result['wasCreated']) {
                        $billingHistoryCount++;
                    }
                }

                // Update renewal count based on actual transactions
                $renewalCount = max(0, count($transactions) - 1); // Subtract 1 for the "created" transaction
                if ($subscription->renewal_count !== $renewalCount) {
                    $subscription->renewal_count = $renewalCount;
                    // Set last_renewed_at from the last transaction if available
                    if (count($transactions) > 1) {
                        $lastTxn = end($transactions);
                        if (isset($lastTxn['purchase_date_ms'])) {
                            $subscription->last_renewed_at = Carbon::createFromTimestampMs((int)$lastTxn['purchase_date_ms']);
                        } elseif (isset($lastTxn['purchase_date'])) {
                            try {
                                $subscription->last_renewed_at = Carbon::parse($lastTxn['purchase_date']);
                            } catch (\Exception $e) {
                                // Skip if date parsing fails
                            }
                        }
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
    private function calculateAndSaveBillingHistory(Subscription $subscription, $paymentSchedule, string $notes, ?float $billingAmount = null, ?string $platformStatus = null): array
    {
        $renewalCount = 0;
        $lastRenewedAt = null;
        // Use provided amount (from platform API if available), otherwise use database amount
        $billingAmount = $billingAmount ?? ($subscription->subscription_amount ?? 0);
        // Use platform status if provided, otherwise default to 'success'
        $status = $platformStatus ?? 'success';
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
                        $result = SubscriptionBilling::createEvent([
                            'subscription_id' => $subscription->id,
                            'user_id' => $subscription->user_id,
                            'platform' => $subscription->platform ?? 'Web',
                            'event_type' => SubscriptionBilling::EVENT_CREATED,
                            'event_date' => $createdDate->format('Y-m-d'),
                            'billing_date' => $createdDate->format('Y-m-d'),
                            'amount' => $billingAmount,
                            'transaction_id' => $subscription->transaction_id,
                            'status' => $status,
                            'notes' => $notes . ' - Subscription created',
                        ]);
                        if ($result['wasCreated']) {
                            $billingHistoryCount++;
                        }
                    }

                    // Record "renewed" events (all subsequent billings)
                    foreach (array_slice($billingDates, 1) as $billingDate) {
                        $result = SubscriptionBilling::createEvent([
                            'subscription_id' => $subscription->id,
                            'user_id' => $subscription->user_id,
                            'platform' => $subscription->platform ?? 'Web',
                            'event_type' => SubscriptionBilling::EVENT_RENEWED,
                            'event_date' => $billingDate->format('Y-m-d'),
                            'billing_date' => $billingDate->format('Y-m-d'),
                            'amount' => $billingAmount,
                            'transaction_id' => $subscription->transaction_id,
                            'status' => $status,
                            'notes' => $notes . ' - Subscription renewed',
                        ]);
                        if ($result['wasCreated']) {
                            $billingHistoryCount++;
                        }
                    }

                    // Record "cancelled" event if cancelled (check platform status)
                    if ($platformStatus && in_array(strtolower($platformStatus), ['cancelled', 'canceled', 'suspended', 'terminated']) && $subscription->cancelled_at) {
                        $cancelledAt = is_string($subscription->cancelled_at) ? Carbon::parse($subscription->cancelled_at) : $subscription->cancelled_at;
                        $result = SubscriptionBilling::createEvent([
                            'subscription_id' => $subscription->id,
                            'user_id' => $subscription->user_id,
                            'platform' => $subscription->platform ?? 'Web',
                            'event_type' => SubscriptionBilling::EVENT_CANCELLED,
                            'event_date' => $cancelledAt->format('Y-m-d'),
                            'status_from' => 'active',
                            'status_to' => strtolower($platformStatus),
                            'notes' => "Subscription cancelled - synced from Authorize.Net (Status: {$platformStatus})",
                            'metadata' => ['cancelled_at' => $cancelledAt->toIso8601String(), 'platform_status' => $platformStatus],
                        ]);
                        if ($result['wasCreated']) {
                            $billingHistoryCount++;
                        }
                    }

                    // Record "expired" event if expired (check platform status)
                    if ($platformStatus && strtolower($platformStatus) === 'expired' && $subscription->expires_at) {
                        $expiresAt = is_string($subscription->expires_at) ? Carbon::parse($subscription->expires_at) : $subscription->expires_at;
                        $result = SubscriptionBilling::createEvent([
                            'subscription_id' => $subscription->id,
                            'user_id' => $subscription->user_id,
                            'platform' => $subscription->platform ?? 'Web',
                            'event_type' => SubscriptionBilling::EVENT_EXPIRED,
                            'event_date' => $expiresAt->format('Y-m-d'),
                            'status_from' => 'active',
                            'status_to' => 'expired',
                            'notes' => 'Subscription expired - synced from Authorize.Net',
                            'metadata' => ['expires_at' => $expiresAt->toIso8601String(), 'platform_status' => $platformStatus],
                        ]);
                        if ($result['wasCreated']) {
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
