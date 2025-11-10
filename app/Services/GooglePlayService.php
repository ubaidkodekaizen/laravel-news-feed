<?php

namespace App\Services;

use Google\Client;
use Google\Service\AndroidPublisher;
use Google\Service\AndroidPublisher\SubscriptionPurchase;
use Google\Service\Exception as GoogleServiceException;
use Illuminate\Support\Facades\Log;

class GooglePlayService
{
    protected AndroidPublisher $androidPublisher;

    protected string $packageName;

    public function __construct()
    {
        $credentialsPath = config('services.google_play.credentials_path');
        if ($credentialsPath && !str_starts_with($credentialsPath, DIRECTORY_SEPARATOR) && !preg_match('/^[A-Za-z]:\\\\/', $credentialsPath)) {
            $credentialsPath = base_path($credentialsPath);
        }
        $this->packageName = (string) config('services.google_play.package_name');

        if (!$credentialsPath || !file_exists($credentialsPath)) {
            throw new \RuntimeException('Google Play credentials file not found. Ensure GOOGLE_PLAY_CREDENTIALS_PATH is set correctly.');
        }

        if (empty($this->packageName)) {
            throw new \RuntimeException('Google Play package name is not configured. Set GOOGLE_PLAY_PACKAGE_NAME in your environment file.');
        }

        $client = new Client();
        $client->setApplicationName(config('app.name', 'Laravel'));
        $client->setAuthConfig($credentialsPath);
        $client->setScopes([AndroidPublisher::ANDROIDPUBLISHER]);
        $client->setAccessType('offline');

        $this->androidPublisher = new AndroidPublisher($client);
    }

    /**
     * Fetch the subscription purchase information from Google Play.
     */
    public function getSubscriptionPurchase(string $productId, string $purchaseToken, ?string $packageName = null): SubscriptionPurchase
    {
        return $this->androidPublisher
            ->purchases_subscriptions
            ->get($packageName ?: $this->packageName, $productId, $purchaseToken);
    }

    /**
     * Acknowledge a subscription purchase if it has not been acknowledged yet.
     *
     * @return bool True when acknowledgement was sent successfully, false if already acknowledged.
     */
    public function acknowledgeSubscription(string $productId, string $purchaseToken, ?string $developerPayload = null, ?string $packageName = null): bool
    {
        try {
            $purchase = $this->getSubscriptionPurchase($productId, $purchaseToken, $packageName);

            if ((int) $purchase->getAcknowledgementState() === 1) {
                // Already acknowledged.
                return false;
            }

            $ackRequest = new AndroidPublisher\SubscriptionPurchasesAcknowledgeRequest();

            if (!empty($developerPayload)) {
                $ackRequest->setDeveloperPayload($developerPayload);
            }

            $this->androidPublisher
                ->purchases_subscriptions
                ->acknowledge(
                    $packageName ?: $this->packageName,
                    $productId,
                    $purchaseToken,
                    $ackRequest
                );

            return true;
        } catch (GoogleServiceException $exception) {
            Log::error('Google Play acknowledgement failed: ' . $exception->getMessage(), [
                'product_id' => $productId,
                'package_name' => $packageName ?: $this->packageName,
            ]);

            throw $exception;
        }
    }
}


