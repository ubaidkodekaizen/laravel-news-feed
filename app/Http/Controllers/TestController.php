<?php

namespace App\Http\Controllers;

use App\Services\GooglePlayService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class TestController extends Controller
{
    /**
     * Test Google Play IAP manual acknowledgment
     */
    public function manualIapAck()
    {
        $productId = 'premium_monthly';
        $purchaseToken = 'pfadblhdjleglpmfiglhokec.AO-J1OyUikJydQcgusdUbomZ44NrIS9Z-MTGX3qgW5vBw_XzDp9R1_1aQMOB4NlM_H1PWXVSNdo0-uY4gajhAp-OKbw8t6TDVw';
        $packageName = 'com.MuslimLynk';

        try {
            $googlePlay = app(GooglePlayService::class);
            $subscription = $googlePlay->getSubscriptionPurchase($productId, $purchaseToken, $packageName);
            $ackState = (int) $subscription->getAcknowledgementState();
            $isAcknowledged = $ackState === 1;

            if (!$isAcknowledged) {
                $googlePlay->acknowledgeSubscription($productId, $purchaseToken, null, $packageName);
                $ackMsg = 'Acknowledgment sent successfully ✅';
            } else {
                $ackMsg = 'Already acknowledged ✅';
            }

            return response()->json([
                'status' => true,
                'message' => $ackMsg,
                'subscription_details' => [
                    'orderId' => $subscription->getOrderId(),
                    'acknowledgementState' => $subscription->getAcknowledgementState(),
                    'expiryTimeMillis' => $subscription->getExpiryTimeMillis(),
                    'autoRenewing' => $subscription->getAutoRenewing(),
                    'priceAmountMicros' => $subscription->getPriceAmountMicros(),
                    'countryCode' => $subscription->getCountryCode(),
                ],
            ]);
        } catch (\Throwable $e) {
            Log::error('Manual acknowledgment failed: ' . $e->getMessage(), [
                'product_id' => $productId,
                'package_name' => $packageName,
            ]);

            return response()->json([
                'status' => false,
                'message' => 'Acknowledgment failed ❌',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Test Google Play service ping
     */
    public function googlePlayPing()
    {
        try {
            app(GooglePlayService::class);
            return response()->json([
                'status' => true,
                'message' => 'Google Play service instantiated successfully. Credentials and configuration look good.',
            ]);
        } catch (\Throwable $exception) {
            Log::error('Google Play ping failed: ' . $exception->getMessage());
            return response()->json([
                'status' => false,
                'message' => 'Unable to instantiate Google Play service. Check configuration and credentials.',
            ], 500);
        }
    }

    /**
     * Test email sending with Laravel Mail
     */
    public function testEmail()
    {
        try {
            Mail::raw('This is a test email from Laravel.', function ($message) {
                $message->to('s.u.shah68@gmail.com')->subject('Laravel Test Email');
            });
            return 'Test email sent successfully!';
        } catch (\Exception $e) {
            return 'Error sending email: ' . $e->getMessage();
        }
    }

    /**
     * Test email sending with PHP mail() function
     */
    public function sendTestEmail()
    {
        $to = 's.u.shah68@gmail.com';
        $subject = 'Test Email from Laravel';
        $message = 'This is a test email.';
        $headers = "From: muslim.lynk@amcob.org\r\n";
        $headers .= "Reply-To: muslim.lynk@amcob.org\r\n";
        $headers .= "X-Mailer: PHP/" . phpversion();

        if (@mail($to, $subject, $message, $headers)) {
            return 'PHP Mail sent successfully!';
        } else {
            return 'PHP Mail failed.';
        }
    }
}
