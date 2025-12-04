<?php
namespace App\Http\Controllers;

use App\Models\Subscription;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use net\authorize\api\contract\v1\IntervalType;
use net\authorize\api\contract\v1\SubscriptionType;
use DB;
use net\authorize\api\contract\v1 as AnetAPI;
use net\authorize\api\controller as AnetController;

class AuthorizeNetController extends Controller
{
    public function index(): View
    {
        return view('auth.authorize-net');
    }

    public function paymentPost(Request $request): RedirectResponse
    {
        //dd($request->all());
        
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required',
            'billing_address' => 'required|string|max:255',
            'city' => 'required|string|max:100',
            'state' => 'required|string|max:100',
            'zip_code' => 'required|string|max:20',
            'country' => 'nullable|string|max:50',
            'card_number' => 'required|numeric',
            'expiration_date' => 'required|date_format:m/y',
            'cvv' => 'required',
            'amount' => 'required|numeric|min:0.01',
        ]);

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
        // $billingAddress->setEmail($request->email);
        $billingAddress->setAddress($request->billing_address);
        $billingAddress->setCity($request->city);
        $billingAddress->setState($request->state);
        $billingAddress->setZip($request->zip_code);
        $billingAddress->setCountry($request->country);
        // $billingAddress->setPhoneNumber($request->phone); 

        $interval = new AnetAPI\PaymentScheduleType\IntervalAType();
        $interval->setLength($request->type === 'Monthly' ? 1 : 12);
        $interval->setUnit('months');

        $paymentSchedule = new AnetAPI\PaymentScheduleType();
        $paymentSchedule->setInterval($interval);
        $paymentSchedule->setStartDate(new \DateTime());
        $paymentSchedule->setTotalOccurrences(9999);
        $paymentSchedule->setTrialOccurrences(0);

        $subscription = new AnetAPI\ARBSubscriptionType();
        $subscription->setAmount($request->amount);
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

        // Handle response as before

        if ($response) {
            $resultCode = $response->getMessages()->getResultCode();

            if ($resultCode === "Ok") {
                $subscriptionId = $response->getSubscriptionId();

                $user = User::create([
                    'first_name' => $request->first_name,
                    'last_name' => $request->last_name,
                    'email' => $request->email,
                    'phone' => $request->phone,
                    'paid' => 'Yes',
                    'status' => 'pending',
                ]);

                $subscription = Subscription::create([
                    'user_id' => $user->id,
                    'plan_id' => $request->plan_id,
                    'subscription_type' => $request->type,
                    'subscription_amount' => $request->amount,
                    'start_date' => now(),
                    'renewal_date' => $request->type === 'Monthly' ? now()->addMonth() : now()->addYear(),
                    'status' => 'active',
                    'transaction_id' => $subscriptionId,
                ]);

                $token = Str::random(64);
              
                DB::table('password_reset_tokens')->updateOrInsert(
                    ['email' => $request->email],
                    ['token' => $token, 'created_at' => now()]
                );
              
              	Mail::send('emails.confirmation-email', [
                    'token' => $token,
                    'user' => $user,
                    'subscription' => $subscription,
                ], function ($message) use ($request) {
                    $message->to($request->email);
                    $message->subject('Verify Email & Setup Password');
                });
                Mail::send('emails.admin-email', [
                    'user' => $user,
                    'subscription' => $subscription,
                ], function ($message) use ($request) {
                    $message->to([
                        "kashif.zubair@amcob.org",
                        "ubaid.syed@kodekaizen.com",
                        "samar.naeem@amcob.org",
                        "kashif.zubair@myadroit.com"
                    ]);

                    $message->subject('A new customer for Muslim Lynk');
                });

              

                return back()->with('success', 'Please check your email to verify your account and set up your password.');
            } else {
                $errorMessages = $response->getMessages()->getMessage();
                $errorMessage = isset($errorMessages[0]) ? $errorMessages[0]->getText() : 'Unknown error occurred';
                return back()->with('error', 'Payment failed: ' . $errorMessage);
            }
        } else {
            return back()->with('error', 'Payment failed: No response from payment gateway.');
        }
    }


}