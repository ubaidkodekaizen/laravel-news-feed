<?php

namespace App\Console\Commands;

use App\Models\Business\Subscription;
use App\Models\SchedulerLog;
use App\Models\User;
use App\Services\GooglePlayService;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use net\authorize\api\contract\v1 as AnetAPI;
use net\authorize\api\controller as AnetController;

class SyncAllSubscriptions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'subscriptions:sync-all';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync subscription status and renewal dates for all platforms (Web/Authorize.Net, Google Play, Apple)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $startTime = microtime(true);
        $ranAt = now();
        
        $this->info('Starting subscription sync for all platforms...');
        $this->newLine();

        $totalUpdated = 0;
        $totalCancelled = 0;
        $totalErrors = 0;
        $webResult = ['updated' => 0, 'cancelled' => 0, 'errors' => 0, 'users' => ['updated' => [], 'cancelled' => [], 'renewed' => []]];
        $googleResult = ['updated' => 0, 'cancelled' => 0, 'errors' => 0, 'users' => ['updated' => [], 'cancelled' => [], 'renewed' => []]];
        $appleResult = ['updated' => 0, 'cancelled' => 0, 'errors' => 0, 'users' => ['updated' => [], 'cancelled' => [], 'renewed' => []]];
        $errorMessage = null;
        $errorTrace = null;
        $status = 'success';

        try {
            // Sync Web/Authorize.Net subscriptions
            $this->info('=== Syncing Web/Authorize.Net Subscriptions ===');
            $webResult = $this->syncAuthorizeNetSubscriptions();
            $totalUpdated += $webResult['updated'];
            $totalCancelled += $webResult['cancelled'];
            $totalErrors += $webResult['errors'];
            $this->newLine();

            // Sync Google Play subscriptions
            $this->info('=== Syncing Google Play Subscriptions ===');
            $googleResult = $this->syncGooglePlaySubscriptions();
            $totalUpdated += $googleResult['updated'];
            $totalCancelled += $googleResult['cancelled'];
            $totalErrors += $googleResult['errors'];
            $this->newLine();

            // Sync Apple subscriptions
            $this->info('=== Syncing Apple Subscriptions ===');
            $appleResult = $this->syncAppleSubscriptions();
            $totalUpdated += $appleResult['updated'];
            $totalCancelled += $appleResult['cancelled'];
            $totalErrors += $appleResult['errors'];
            $this->newLine();

            // Determine status
            if ($totalErrors > 0 && ($totalUpdated > 0 || $totalCancelled > 0)) {
                $status = 'partial';
            } elseif ($totalErrors > 0) {
                $status = 'failed';
            }

        } catch (\Exception $e) {
            $status = 'failed';
            $errorMessage = $e->getMessage();
            $errorTrace = $e->getTraceAsString();
            $this->error('Fatal error: ' . $errorMessage);
            Log::error('Scheduler sync failed: ' . $errorMessage, [
                'trace' => $errorTrace,
            ]);
        }

        $executionTime = (int) ((microtime(true) - $startTime) * 1000); // Convert to milliseconds

        $this->info("=== Sync Complete ===");
        $this->info("Total Updated: {$totalUpdated}");
        $this->info("Total Cancelled: {$totalCancelled}");
        $this->info("Total Errors: {$totalErrors}");
        $this->info("Execution Time: {$executionTime}ms");

        // Calculate total records processed
        $totalProcessed = $webResult['updated'] + $webResult['cancelled'] + $webResult['errors'] +
                         $googleResult['updated'] + $googleResult['cancelled'] + $googleResult['errors'] +
                         $appleResult['updated'] + $appleResult['cancelled'] + $appleResult['errors'];

        // Build detailed result text
        $resultDetail = $this->buildResultDetail($totalUpdated, $totalCancelled, $totalErrors, $webResult, $googleResult, $appleResult);

        // Build structured result data (JSON)
        $resultData = [
            'total_updated' => $totalUpdated,
            'total_cancelled' => $totalCancelled,
            'total_errors' => $totalErrors,
            'platforms' => [
                'web' => [
                    'updated' => $webResult['updated'],
                    'cancelled' => $webResult['cancelled'],
                    'errors' => $webResult['errors'],
                    'users' => $webResult['users'] ?? ['updated' => [], 'cancelled' => [], 'renewed' => []],
                ],
                'google' => [
                    'updated' => $googleResult['updated'],
                    'cancelled' => $googleResult['cancelled'],
                    'errors' => $googleResult['errors'],
                    'users' => $googleResult['users'] ?? ['updated' => [], 'cancelled' => [], 'renewed' => []],
                ],
                'apple' => [
                    'updated' => $appleResult['updated'],
                    'cancelled' => $appleResult['cancelled'],
                    'errors' => $appleResult['errors'],
                    'users' => $appleResult['users'] ?? ['updated' => [], 'cancelled' => [], 'renewed' => []],
                ],
            ],
        ];

        // Log to database
        SchedulerLog::create([
            'scheduler' => 'subscriptions:sync-all',
            'command' => 'subscriptions:sync-all',
            'status' => $status,
            'result_detail' => $resultDetail,
            'result_data' => $resultData,
            'records_processed' => $totalProcessed,
            'records_updated' => $totalUpdated,
            'records_failed' => $totalErrors,
            'error_message' => $errorMessage,
            'error_trace' => $errorTrace,
            'execution_time_ms' => $executionTime,
            'ran_at' => $ranAt,
        ]);

        return $status === 'failed' ? 1 : 0;
    }

    /**
     * Build detailed result string for logging
     */
    private function buildResultDetail(int $totalUpdated, int $totalCancelled, int $totalErrors, array $webResult, array $googleResult, array $appleResult): string
    {
        $details = [];
        
        $details[] = "=== Subscription Sync Results ===";
        $details[] = "Total Updated: {$totalUpdated}";
        $details[] = "Total Cancelled: {$totalCancelled}";
        $details[] = "Total Errors: {$totalErrors}";
        $details[] = "";
        $details[] = "=== Platform Breakdown ===";
        $details[] = "Web/Authorize.Net: Updated={$webResult['updated']}, Cancelled={$webResult['cancelled']}, Errors={$webResult['errors']}";
        $details[] = "Google Play: Updated={$googleResult['updated']}, Cancelled={$googleResult['cancelled']}, Errors={$googleResult['errors']}";
        $details[] = "Apple: Updated={$appleResult['updated']}, Cancelled={$appleResult['cancelled']}, Errors={$appleResult['errors']}";
        
        return implode("\n", $details);
    }

    /**
     * Sync Authorize.Net (Web platform) subscriptions
     */
    private function syncAuthorizeNetSubscriptions(): array
    {
        $subscriptions = Subscription::with('user')
            ->where('platform', 'Web')
            ->where('status', 'active')
            ->whereNotNull('transaction_id')
            ->get();

        if ($subscriptions->isEmpty()) {
            $this->info('No Web platform subscriptions found.');
            return ['updated' => 0, 'cancelled' => 0, 'errors' => 0];
        }

        $this->info("Found {$subscriptions->count()} Web subscription(s) to check.");

        $merchantAuthentication = new AnetAPI\MerchantAuthenticationType();
        $merchantAuthentication->setName(env('AUTHORIZENET_API_LOGIN_ID'));
        $merchantAuthentication->setTransactionKey(env('AUTHORIZENET_TRANSACTION_KEY'));

        $updatedCount = 0;
        $cancelledCount = 0;
        $errorCount = 0;

        foreach ($subscriptions as $subscription) {
            try {
                // Get subscription status from Authorize.Net
                $request = new AnetAPI\ARBGetSubscriptionStatusRequest();
                $request->setMerchantAuthentication($merchantAuthentication);
                $request->setSubscriptionId($subscription->transaction_id);

                $controller = new AnetController\ARBGetSubscriptionStatusController($request);
                $response = $controller->executeWithApiResponse(\net\authorize\api\constants\ANetEnvironment::PRODUCTION);

                if ($response && $response->getMessages()->getResultCode() === "Ok") {
                    $anetStatus = strtolower($response->getStatus());
                    
                    // Try to get actual next billing date from full subscription details
                    $nextBillingDate = null;
                    try {
                        $detailRequest = new AnetAPI\ARBGetSubscriptionRequest();
                        $detailRequest->setMerchantAuthentication($merchantAuthentication);
                        $detailRequest->setSubscriptionId($subscription->transaction_id);
                        $detailController = new AnetController\ARBGetSubscriptionController($detailRequest);
                        $detailResponse = $detailController->executeWithApiResponse(\net\authorize\api\constants\ANetEnvironment::PRODUCTION);
                        
                        if ($detailResponse && $detailResponse->getMessages()->getResultCode() === "Ok") {
                            $subscriptionDetails = $detailResponse->getSubscription();
                            $paymentSchedule = $subscriptionDetails->getPaymentSchedule();
                            
                            if ($paymentSchedule) {
                                $startDate = $paymentSchedule->getStartDate();
                                $interval = $paymentSchedule->getInterval();
                                
                                if ($startDate && $interval) {
                                    $intervalLength = $interval->getLength();
                                    $intervalUnit = $interval->getUnit();
                                    
                                    if ($intervalLength && $intervalUnit) {
                                        $nextBilling = Carbon::parse($startDate);
                                        while ($nextBilling->lte(Carbon::now())) {
                                            if ($intervalUnit === 'months') {
                                                $nextBilling->addMonths($intervalLength);
                                            } elseif ($intervalUnit === 'days') {
                                                $nextBilling->addDays($intervalLength);
                                            } else {
                                                break;
                                            }
                                        }
                                        $nextBillingDate = $nextBilling->format('Y-m-d');
                                    }
                                }
                            }
                        }
                    } catch (\Exception $e) {
                        // Fall back to calculation if detail request fails
                        Log::debug("Could not get subscription details for ID {$subscription->transaction_id}: " . $e->getMessage());
                    }
                    
                    // Fallback: Calculate next billing date based on subscription type
                    if (!$nextBillingDate) {
                        $nextBillingDate = Carbon::parse($subscription->start_date);
                        $subscriptionType = strtolower($subscription->subscription_type ?? '');
                        
                        if (strpos($subscriptionType, 'monthly') !== false || strpos($subscriptionType, 'month') !== false) {
                            while ($nextBillingDate->lte(Carbon::now())) {
                                $nextBillingDate->addMonth();
                            }
                        } else {
                            while ($nextBillingDate->lte(Carbon::now())) {
                                $nextBillingDate->addYear();
                            }
                        }
                        $nextBillingDate = $nextBillingDate->format('Y-m-d');
                    }

                    if ($anetStatus === 'active') {
                        $needsSave = false;
                        $oldRenewalDate = $subscription->renewal_date;
                        
                        $isRenewal = false;
                        // Update renewal date if we got it from API
                        if ($nextBillingDate && $subscription->renewal_date != $nextBillingDate) {
                            $subscription->renewal_date = $nextBillingDate;
                            $subscription->expires_at = Carbon::parse($nextBillingDate)->endOfDay();
                            $needsSave = true;
                            
                            // Check if this is a renewal (renewal date increased)
                            if ($oldRenewalDate && Carbon::parse($nextBillingDate)->gt(Carbon::parse($oldRenewalDate))) {
                                $subscription->renewal_count = ($subscription->renewal_count ?? 0) + 1;
                                $subscription->last_renewed_at = now();
                                $isRenewal = true;
                                $this->info("Subscription ID {$subscription->id} - Renewed! Count: {$subscription->renewal_count}");
                            }
                        }
                        
                        // Ensure status is active
                        if ($subscription->status !== 'active') {
                            $subscription->status = 'active';
                            $needsSave = true;
                        }
                        
                        $subscription->auto_renewing = true;
                        $subscription->payment_state = 'active';
                        $subscription->last_checked_at = now();
                        
                        if ($needsSave) {
                            $subscription->save();
                            $this->updateUserPaidStatus($subscription->user_id);
                            $updatedCount++;
                            $user = $subscription->user;
                            $userInfo = [
                                'user_id' => $user->id ?? null,
                                'email' => $user->email ?? 'N/A',
                                'name' => trim(($user->first_name ?? '') . ' ' . ($user->last_name ?? '')) ?: 'N/A',
                                'subscription_id' => $subscription->id,
                                'platform' => 'Web',
                                'renewal_date' => $subscription->renewal_date,
                            ];
                            if ($isRenewal) {
                                $webResult['users']['renewed'][] = $userInfo;
                            } else {
                                $webResult['users']['updated'][] = $userInfo;
                            }
                            $this->info("Subscription ID {$subscription->id} - Updated (Renewal: {$subscription->renewal_date})");
                        } else {
                            $subscription->last_checked_at = now();
                            $subscription->save();
                        }
                    } else {
                        // Handle cancelled/expired/suspended statuses
                        $subscription->status = 'cancelled';
                        $subscription->cancelled_at = now();
                        $subscription->auto_renewing = false;
                        $subscription->payment_state = $anetStatus;
                        $subscription->last_checked_at = now();
                        $subscription->save();
                        
                        $this->updateUserPaidStatus($subscription->user_id);
                        $cancelledCount++;
                        $user = $subscription->user;
                        $webResult['users']['cancelled'][] = [
                            'user_id' => $user->id ?? null,
                            'email' => $user->email ?? 'N/A',
                            'name' => trim(($user->first_name ?? '') . ' ' . ($user->last_name ?? '')) ?: 'N/A',
                            'subscription_id' => $subscription->id,
                            'platform' => 'Web',
                            'reason' => ucfirst($anetStatus),
                        ];
                        $this->warn("Subscription ID {$subscription->id} - Status: {$anetStatus} → Cancelled");
                    }
                } else {
                    $errorMessages = $response->getMessages()->getMessage();
                    $errorMessage = isset($errorMessages[0]) ? $errorMessages[0]->getText() : 'Unknown error';
                    
                    if (stripos($errorMessage, 'invalid') !== false || stripos($errorMessage, 'not found') !== false) {
                        $subscription->status = 'cancelled';
                        $subscription->cancelled_at = now();
                        $subscription->last_checked_at = now();
                        $subscription->save();
                        $this->updateUserPaidStatus($subscription->user_id);
                        $cancelledCount++;
                        $user = $subscription->user;
                        $webResult['users']['cancelled'][] = [
                            'user_id' => $user->id ?? null,
                            'email' => $user->email ?? 'N/A',
                            'name' => trim(($user->first_name ?? '') . ' ' . ($user->last_name ?? '')) ?: 'N/A',
                            'subscription_id' => $subscription->id,
                            'platform' => 'Web',
                            'reason' => 'Invalid Subscription ID',
                        ];
                        $this->warn("Subscription ID {$subscription->id} - Invalid ID → Cancelled");
                    } else {
                        $this->error("Subscription ID {$subscription->id} - Error: {$errorMessage}");
                        $errorCount++;
                    }
                }
            } catch (\Exception $e) {
                $this->error("Error processing subscription ID {$subscription->id}: " . $e->getMessage());
                Log::error("Authorize.Net sync error for subscription {$subscription->id}: " . $e->getMessage());
                $errorCount++;
            }
        }

        $this->info("Web: Updated: {$updatedCount}, Cancelled: {$cancelledCount}, Errors: {$errorCount}");
        return ['updated' => $updatedCount, 'cancelled' => $cancelledCount, 'errors' => $errorCount];
    }

    /**
     * Sync Google Play subscriptions
     */
    private function syncGooglePlaySubscriptions(): array
    {
        $subscriptions = Subscription::with('user')
            ->where('platform', 'google')
            ->where('status', 'active')
            ->whereNotNull('transaction_id')
            ->get();

        if ($subscriptions->isEmpty()) {
            $this->info('No Google Play subscriptions found.');
            return ['updated' => 0, 'cancelled' => 0, 'errors' => 0, 'users' => ['updated' => [], 'cancelled' => [], 'renewed' => []]];
        }

        $this->info("Found {$subscriptions->count()} Google Play subscription(s) to check.");

        try {
            $googlePlay = app(GooglePlayService::class);
        } catch (\Exception $e) {
            $this->error("Failed to initialize Google Play service: " . $e->getMessage());
            return ['updated' => 0, 'cancelled' => 0, 'errors' => $subscriptions->count(), 'users' => ['updated' => [], 'cancelled' => [], 'renewed' => []]];
        }

        $updatedCount = 0;
        $cancelledCount = 0;
        $errorCount = 0;
        $googleResult = ['updated' => 0, 'cancelled' => 0, 'errors' => 0, 'users' => ['updated' => [], 'cancelled' => [], 'renewed' => []]];

        foreach ($subscriptions as $subscription) {
            try {
                // Get product ID from subscription type
                $planType = $subscription->subscription_type === 'Monthly' ? 'Premium_Monthly' : 'Premium_Yearly';
                $productId = config("services.google_play.products.{$planType}");
                
                if (!$productId) {
                    $this->warn("Subscription ID {$subscription->id} - Product ID not configured for type: {$planType}");
                    $errorCount++;
                    continue;
                }

                // Get purchase token from transaction_id or receipt_data
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

                // Get subscription status from Google Play
                $purchase = $googlePlay->getSubscriptionPurchase($productId, $purchaseToken);
                
                $expiryTimeMillis = $purchase->getExpiryTimeMillis();
                $expiryDate = $expiryTimeMillis ? Carbon::createFromTimestampMs($expiryTimeMillis) : null;
                $paymentState = (int) $purchase->getPaymentState();
                $autoRenewing = (bool) $purchase->getAutoRenewing();
                $cancelReason = $purchase->getCancelReason();
                
                $needsSave = false;
                $oldExpiryDate = $subscription->expires_at ? Carbon::parse($subscription->expires_at) : null;
                
                // Check if subscription is still active
                $isActive = $expiryDate && $expiryDate->isFuture() && $paymentState === 1 && $autoRenewing;
                
                if ($isActive && !$cancelReason) {
                    // Active subscription
                    if ($subscription->status !== 'active') {
                        $subscription->status = 'active';
                        $needsSave = true;
                    }
                    
                    $isRenewal = false;
                    // Update renewal date (expiry date)
                    $newRenewalDate = $expiryDate->format('Y-m-d');
                    if ($subscription->renewal_date != $newRenewalDate) {
                        // Check if this is a renewal (expiry date increased)
                        if ($oldExpiryDate && $expiryDate->gt($oldExpiryDate)) {
                            $subscription->renewal_count = ($subscription->renewal_count ?? 0) + 1;
                            $subscription->last_renewed_at = now();
                            $isRenewal = true;
                            $this->info("Subscription ID {$subscription->id} - Renewed! Count: {$subscription->renewal_count}");
                        }
                        
                        $subscription->renewal_date = $newRenewalDate;
                        $subscription->expires_at = $expiryDate;
                        $needsSave = true;
                    } else {
                        $subscription->expires_at = $expiryDate;
                        $needsSave = true;
                    }
                    
                    $subscription->auto_renewing = $autoRenewing;
                    $subscription->payment_state = $this->mapGooglePaymentState($paymentState);
                    $subscription->last_checked_at = now();
                    
                    if ($needsSave) {
                        $subscription->save();
                        $this->updateUserPaidStatus($subscription->user_id);
                        $updatedCount++;
                        $user = $subscription->user;
                        $userInfo = [
                            'user_id' => $user->id ?? null,
                            'email' => $user->email ?? 'N/A',
                            'name' => trim(($user->first_name ?? '') . ' ' . ($user->last_name ?? '')) ?: 'N/A',
                            'subscription_id' => $subscription->id,
                            'platform' => 'Google Play',
                            'renewal_date' => $subscription->renewal_date,
                        ];
                        if ($isRenewal) {
                            $googleResult['users']['renewed'][] = $userInfo;
                        } else {
                            $googleResult['users']['updated'][] = $userInfo;
                        }
                        $this->info("Subscription ID {$subscription->id} - Updated (Expires: {$newRenewalDate})");
                    } else {
                        $subscription->last_checked_at = now();
                        $subscription->save();
                    }
                } else {
                    // Cancelled or expired
                    $subscription->status = 'cancelled';
                    $subscription->cancelled_at = now();
                    $subscription->auto_renewing = false;
                    $subscription->payment_state = $this->mapGooglePaymentState($paymentState);
                    $subscription->expires_at = $expiryDate;
                    $subscription->last_checked_at = now();
                    $subscription->save();
                    
                    $this->updateUserPaidStatus($subscription->user_id);
                    $cancelledCount++;
                    $user = $subscription->user;
                    $cancelReasonText = $cancelReason ? ucfirst($cancelReason) : ($paymentState !== 1 ? 'Payment Issue' : 'Expired');
                    $googleResult['users']['cancelled'][] = [
                        'user_id' => $user->id ?? null,
                        'email' => $user->email ?? 'N/A',
                        'name' => trim(($user->first_name ?? '') . ' ' . ($user->last_name ?? '')) ?: 'N/A',
                        'subscription_id' => $subscription->id,
                        'platform' => 'Google Play',
                        'reason' => $cancelReasonText,
                    ];
                    $this->warn("Subscription ID {$subscription->id} - Cancelled/Expired (Reason: {$cancelReason}, Payment State: {$paymentState})");
                }
            } catch (\Exception $e) {
                $this->error("Error processing subscription ID {$subscription->id}: " . $e->getMessage());
                Log::error("Google Play sync error for subscription {$subscription->id}: " . $e->getMessage());
                $errorCount++;
            }
        }

        $this->info("Google Play: Updated: {$updatedCount}, Cancelled: {$cancelledCount}, Errors: {$errorCount}");
        $googleResult['updated'] = $updatedCount;
        $googleResult['cancelled'] = $cancelledCount;
        $googleResult['errors'] = $errorCount;
        return $googleResult;
    }

    /**
     * Sync Apple subscriptions
     */
    private function syncAppleSubscriptions(): array
    {
        $subscriptions = Subscription::with('user')
            ->where('platform', 'apple')
            ->where('status', 'active')
            ->whereNotNull('transaction_id')
            ->get();

        if ($subscriptions->isEmpty()) {
            $this->info('No Apple subscriptions found.');
            return ['updated' => 0, 'cancelled' => 0, 'errors' => 0, 'users' => ['updated' => [], 'cancelled' => [], 'renewed' => []]];
        }

        $this->info("Found {$subscriptions->count()} Apple subscription(s) to check.");

        $updatedCount = 0;
        $cancelledCount = 0;
        $errorCount = 0;

        foreach ($subscriptions as $subscription) {
            try {
                // Apple uses App Store Server API
                // We need original_transaction_id for server-to-server API
                $originalTransactionId = $subscription->transaction_id;
                $receiptData = $subscription->receipt_data ? json_decode($subscription->receipt_data, true) : [];
                
                if (isset($receiptData['original_transaction_id'])) {
                    $originalTransactionId = $receiptData['original_transaction_id'];
                }

                // For now, we'll use receipt validation API as fallback
                // In production, you should use App Store Server API (JWT-based)
                $appleStatus = $this->checkAppleSubscriptionStatus($subscription, $originalTransactionId);
                
                if ($appleStatus === null) {
                    $this->warn("Subscription ID {$subscription->id} - Could not verify Apple subscription");
                    $errorCount++;
                    continue;
                }

                $isActive = $appleStatus['is_active'] ?? false;
                $expiresDate = $appleStatus['expires_date'] ?? null;
                $autoRenewing = $appleStatus['auto_renewing'] ?? false;
                $statusCode = $appleStatus['status'] ?? null;

                if ($isActive && $expiresDate) {
                    $needsSave = false;
                    $oldExpiryDate = $subscription->expires_at ? Carbon::parse($subscription->expires_at) : null;
                    $expiryCarbon = Carbon::parse($expiresDate);
                    $newRenewalDate = $expiryCarbon->format('Y-m-d');
                    $isRenewal = false;
                    
                    // Update renewal date
                    if ($subscription->renewal_date != $newRenewalDate) {
                        // Check if this is a renewal
                        if ($oldExpiryDate && $expiryCarbon->gt($oldExpiryDate)) {
                            $subscription->renewal_count = ($subscription->renewal_count ?? 0) + 1;
                            $subscription->last_renewed_at = now();
                            $isRenewal = true;
                            $this->info("Subscription ID {$subscription->id} - Renewed! Count: {$subscription->renewal_count}");
                        }
                        
                        $subscription->renewal_date = $newRenewalDate;
                        $subscription->expires_at = $expiryCarbon;
                        $needsSave = true;
                    } else {
                        $subscription->expires_at = $expiryCarbon;
                        $needsSave = true;
                    }
                    
                    if ($subscription->status !== 'active') {
                        $subscription->status = 'active';
                        $needsSave = true;
                    }
                    
                    $subscription->auto_renewing = $autoRenewing;
                    $subscription->payment_state = $this->mapAppleStatus($statusCode);
                    $subscription->last_checked_at = now();
                    
                    if ($needsSave) {
                        $subscription->save();
                        $this->updateUserPaidStatus($subscription->user_id);
                        $updatedCount++;
                        $user = $subscription->user;
                        $userInfo = [
                            'user_id' => $user->id ?? null,
                            'email' => $user->email ?? 'N/A',
                            'name' => trim(($user->first_name ?? '') . ' ' . ($user->last_name ?? '')) ?: 'N/A',
                            'subscription_id' => $subscription->id,
                            'platform' => 'Apple',
                            'renewal_date' => $subscription->renewal_date,
                        ];
                        if ($isRenewal) {
                            $appleResult['users']['renewed'][] = $userInfo;
                        } else {
                            $appleResult['users']['updated'][] = $userInfo;
                        }
                        $this->info("Subscription ID {$subscription->id} - Updated (Expires: {$newRenewalDate})");
                    } else {
                        $subscription->last_checked_at = now();
                        $subscription->save();
                    }
                } else {
                    // Cancelled or expired
                    $subscription->status = 'cancelled';
                    $subscription->cancelled_at = now();
                    $subscription->auto_renewing = false;
                    $subscription->payment_state = $this->mapAppleStatus($statusCode);
                    if ($expiresDate) {
                        $subscription->expires_at = Carbon::parse($expiresDate);
                    }
                    $subscription->last_checked_at = now();
                    $subscription->save();
                    
                    $this->updateUserPaidStatus($subscription->user_id);
                    $cancelledCount++;
                    $user = $subscription->user;
                    $cancelReasonText = $this->mapAppleStatus($statusCode);
                    $appleResult['users']['cancelled'][] = [
                        'user_id' => $user->id ?? null,
                        'email' => $user->email ?? 'N/A',
                        'name' => trim(($user->first_name ?? '') . ' ' . ($user->last_name ?? '')) ?: 'N/A',
                        'subscription_id' => $subscription->id,
                        'platform' => 'Apple',
                        'reason' => ucfirst($cancelReasonText),
                    ];
                    $this->warn("Subscription ID {$subscription->id} - Cancelled/Expired");
                }
            } catch (\Exception $e) {
                $this->error("Error processing subscription ID {$subscription->id}: " . $e->getMessage());
                Log::error("Apple sync error for subscription {$subscription->id}: " . $e->getMessage());
                $errorCount++;
            }
        }

        $this->info("Apple: Updated: {$updatedCount}, Cancelled: {$cancelledCount}, Errors: {$errorCount}");
        $appleResult['updated'] = $updatedCount;
        $appleResult['cancelled'] = $cancelledCount;
        $appleResult['errors'] = $errorCount;
        return $appleResult;
    }

    /**
     * Check Apple subscription status using App Store Receipt Validation
     * Note: This is a simplified version. For production, use App Store Server API
     */
    private function checkAppleSubscriptionStatus(Subscription $subscription, string $originalTransactionId): ?array
    {
        // For Apple, we'll use a simplified approach
        // In production, you should implement App Store Server API (JWT-based)
        // or use receipt validation API
        
        // For now, we'll check based on expiry date calculation
        // This is a placeholder - you should implement proper Apple API calls
        
        $receiptData = $subscription->receipt_data ? json_decode($subscription->receipt_data, true) : [];
        
        // If we have expiry date in receipt data, use it
        if (isset($receiptData['expires_date_ms'])) {
            $expiresDate = Carbon::createFromTimestampMs($receiptData['expires_date_ms']);
            $isActive = $expiresDate->isFuture();
            
            return [
                'is_active' => $isActive,
                'expires_date' => $expiresDate->toDateTimeString(),
                'auto_renewing' => $receiptData['auto_renew_status'] ?? true,
                'status' => $isActive ? 1 : 2,
            ];
        }
        
        // Fallback: Calculate from renewal_date
        if ($subscription->renewal_date) {
            $expiresDate = Carbon::parse($subscription->renewal_date);
            $isActive = $expiresDate->isFuture();
            
            return [
                'is_active' => $isActive,
                'expires_date' => $expiresDate->toDateTimeString(),
                'auto_renewing' => true,
                'status' => $isActive ? 1 : 2,
            ];
        }
        
        return null;
    }

    /**
     * Map Google Play payment state to readable string
     */
    private function mapGooglePaymentState(int $paymentState): string
    {
        return match($paymentState) {
            0 => 'free_trial',
            1 => 'payment_received',
            2 => 'pending',
            default => 'unknown',
        };
    }

    /**
     * Map Apple status code to readable string
     */
    private function mapAppleStatus(?int $statusCode): string
    {
        return match($statusCode) {
            1 => 'active',
            2 => 'expired',
            3 => 'billing_retry',
            4 => 'billing_retry_period_expired',
            5 => 'refunded',
            default => 'unknown',
        };
    }

    /**
     * Update user's paid status based on active subscriptions
     */
    private function updateUserPaidStatus(int $userId): void
    {
        $user = User::find($userId);
        if (!$user) {
            return;
        }

        $hasActiveSubscription = Subscription::where('user_id', $userId)
            ->where('status', 'active')
            ->exists();

        if ($hasActiveSubscription) {
            if ($user->paid !== 'Yes') {
                $user->update(['paid' => 'Yes']);
                $this->line("User ID {$userId} - Updated paid status to 'Yes'");
            }
        } else {
            if ($user->paid !== 'No') {
                $user->update(['paid' => 'No']);
                $this->line("User ID {$userId} - Updated paid status to 'No'");
            }
        }
    }
}
