<?php

namespace App\Http\Controllers;

use App\Models\Subscription;
use App\Models\Plan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\RedirectResponse;
use net\authorize\api\contract\v1 as AnetAPI;
use net\authorize\api\controller as AnetController;

class SubscriptionController extends Controller
{
    /**
     * Display a list of user education records.
     */
    public function index()
    {
        $subscriptions = Subscription::where('user_id', Auth::id())->get();
        
        // Find active subscription with plan relationship
        $activeSubscription = Subscription::where('user_id', Auth::id())
            ->where('status', 'active')
            ->with('plan')
            ->latest()
            ->first();
        
        // Find monthly and yearly plans by amount
        $monthlyPlan = \App\Models\Plan::where(function($query) {
            $query->where('plan_amount', 4.99)
                  ->orWhere('plan_amount', 5.00)
                  ->orWhere('plan_amount', 5);
        })->first();
        
        $yearlyPlan = \App\Models\Plan::where(function($query) {
            $query->where('plan_amount', 49.99)
                  ->orWhere('plan_amount', 50.00)
                  ->orWhere('plan_amount', 50);
        })->first();
        
        $monthlyPlanId = $monthlyPlan ? $monthlyPlan->id : null;
        $yearlyPlanId = $yearlyPlan ? $yearlyPlan->id : null;
        
        // Determine if monthly or yearly is active based on subscription_type
        $isMonthlyActive = false;
        $isYearlyActive = false;
        $renewalDate = null;
        $platform = null;
        
        if ($activeSubscription && $activeSubscription->subscription_type) {
            $subscriptionType = strtolower($activeSubscription->subscription_type);
            if (strpos($subscriptionType, 'monthly') !== false || strpos($subscriptionType, 'month') !== false) {
                $isMonthlyActive = true;
            } elseif (strpos($subscriptionType, 'yearly') !== false || strpos($subscriptionType, 'year') !== false) {
                $isYearlyActive = true;
            }
            $renewalDate = $activeSubscription->renewal_date;
            $platform = $activeSubscription->platform;
        }
        
        return view('user.user-subscriptions', compact('subscriptions', 'isMonthlyActive', 'isYearlyActive', 'renewalDate', 'platform', 'monthlyPlanId', 'yearlyPlanId'));
    }


    public function addSubscription(Request $request)
    {
        $planId = $request->get('plan_id');
        $selectedPlan = null;
        $user = Auth::user();
        
        if ($planId) {
            $selectedPlan = Plan::find($planId);
        }
        
        return view('user.add-subscription', compact('selectedPlan', 'planId', 'user'));
    }

    public function processPayment(Request $request): RedirectResponse
    {
        $user = Auth::user();
        
        $request->validate([
            'plan_id' => 'required|exists:plans,id',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email',
            'phone' => 'required|string|max:20',
            'card_number' => 'required|numeric',
            'expiration_date' => 'required|date_format:m/y',
            'cvv' => 'required',
            'billing_address' => 'required|string|max:255',
            'city' => 'required|string|max:100',
            'state' => 'required|string|max:100',
            'zip_code' => 'required|string|max:20',
            'country' => 'nullable|string|max:50',
        ]);

        $plan = Plan::findOrFail($request->plan_id);
        $amount = floatval($plan->plan_amount);
        
        // Determine subscription type based on amount
        $subscriptionType = ($amount >= 4 && $amount <= 6) ? 'Monthly' : 'Yearly';

        $merchantAuthentication = new AnetAPI\MerchantAuthenticationType();
        $merchantAuthentication->setName(env('AUTHORIZENET_API_LOGIN_ID'));
        $merchantAuthentication->setTransactionKey(env('AUTHORIZENET_TRANSACTION_KEY'));

        $creditCard = new AnetAPI\CreditCardType();
        $creditCard->setCardNumber($request->card_number);
        $creditCard->setExpirationDate($request->expiration_date);
        $creditCard->setCardCode($request->cvv);

        $payment = new AnetAPI\PaymentType();
        $payment->setCreditCard($creditCard);

        $billingAddress = new AnetAPI\CustomerAddressType();
        $billingAddress->setFirstName($request->first_name);
        $billingAddress->setLastName($request->last_name);
        $billingAddress->setAddress($request->billing_address);
        $billingAddress->setCity($request->city);
        $billingAddress->setState($request->state);
        $billingAddress->setZip($request->zip_code);
        $billingAddress->setCountry($request->country ?? '');

        $interval = new AnetAPI\PaymentScheduleType\IntervalAType();
        $interval->setLength($subscriptionType === 'Monthly' ? 1 : 12);
        $interval->setUnit('months');

        $paymentSchedule = new AnetAPI\PaymentScheduleType();
        $paymentSchedule->setInterval($interval);
        $paymentSchedule->setStartDate(new \DateTime());
        $paymentSchedule->setTotalOccurrences(9999);
        $paymentSchedule->setTrialOccurrences(0);

        $subscription = new AnetAPI\ARBSubscriptionType();
        $subscription->setAmount($amount);
        $subscription->setPayment($payment);
        $subscription->setBillTo($billingAddress);
        $subscription->setName("Subscription for {$request->first_name} {$request->last_name}");
        $subscription->setPaymentSchedule($paymentSchedule);
        $subscription->setTrialAmount(0.00);

        $apiRequest = new AnetAPI\ARBCreateSubscriptionRequest();
        $apiRequest->setMerchantAuthentication($merchantAuthentication);
        $apiRequest->setSubscription($subscription);

        $controller = new AnetController\ARBCreateSubscriptionController($apiRequest);
        $response = $controller->executeWithApiResponse(\net\authorize\api\constants\ANetEnvironment::PRODUCTION);

        if ($response) {
            $resultCode = $response->getMessages()->getResultCode();

            if ($resultCode === "Ok") {
                $subscriptionId = $response->getSubscriptionId();

                // Cancel any existing active subscriptions
                Subscription::where('user_id', $user->id)
                    ->where('status', 'active')
                    ->update(['status' => 'cancelled']);

                $subscription = Subscription::create([
                    'user_id' => $user->id,
                    'plan_id' => $request->plan_id,
                    'subscription_type' => $subscriptionType,
                    'subscription_amount' => $amount,
                    'start_date' => now(),
                    'renewal_date' => $subscriptionType === 'Monthly' ? now()->addMonth() : now()->addYear(),
                    'status' => 'active',
                    'transaction_id' => $subscriptionId,
                    'platform' => 'Web',
                ]);

                $user->update(['paid' => 'Yes']);

                return redirect()->route('user.subscriptions')->with('success', 'Subscription activated successfully!');
            } else {
                $errorMessages = $response->getMessages()->getMessage();
                $errorMessage = isset($errorMessages[0]) ? $errorMessages[0]->getText() : 'Unknown error occurred';
                return back()->with('error', 'Payment failed: ' . $errorMessage)->withInput();
            }
        } else {
            return back()->with('error', 'Payment failed: No response from payment gateway.')->withInput();
        }
    }
}
