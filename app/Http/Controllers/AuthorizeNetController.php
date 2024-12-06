<?php

namespace App\Http\Controllers;

use App\Models\Subscription;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
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

        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'billing_address' => 'required|string|max:255',
            'city' => 'required|string|max:100',
            'state' => 'required|string|max:100',
            'zip_code' => 'required|string|max:20',
            'country' => 'nullable|string|max:50',
        ]);

        $validatedData = $request->validate([
            'card_number' => 'required|numeric',
            'expiration_date' => 'required|date_format:m/y',
            'cvv' => 'required',
            'amount' => 'required|numeric|min:0.01',
        ]);


        $apiLoginId = env('AUTHORIZENET_API_LOGIN_ID');
        $transactionKey = env('AUTHORIZENET_TRANSACTION_KEY');

        if (!$apiLoginId || !$transactionKey) {
            return back()->with('error', 'Authorize.Net API credentials are missing.');
        }

        $merchantAuthentication = new AnetAPI\MerchantAuthenticationType();
        $merchantAuthentication->setName($apiLoginId);
        $merchantAuthentication->setTransactionKey($transactionKey);

        $creditCard = new AnetAPI\CreditCardType();
        $creditCard->setCardNumber($validatedData['card_number']);
        $creditCard->setExpirationDate($validatedData['expiration_date']);
        $creditCard->setCardCode($validatedData['cvv']);


        $payment = new AnetAPI\PaymentType();
        $payment->setCreditCard($creditCard);

        $transactionRequestType = new AnetAPI\TransactionRequestType();
        $transactionRequestType->setTransactionType("authCaptureTransaction");
        $transactionRequestType->setAmount($validatedData['amount']);
        $transactionRequestType->setPayment($payment);

        $apiRequest = new AnetAPI\CreateTransactionRequest();
        $apiRequest->setMerchantAuthentication($merchantAuthentication);
        $apiRequest->setRefId("ref" . time());
        $apiRequest->setTransactionRequest($transactionRequestType);

    
        $billingAddress = new AnetAPI\CustomerAddressType();
        $billingAddress->setFirstName($request->first_name);
        $billingAddress->setLastName($request->last_name);
        $billingAddress->setAddress($request->billing_address);
        $billingAddress->setCity($request->city); 
        $billingAddress->setState($request->state);
        $billingAddress->setZip($request->zip_code);
        $billingAddress->setCountry($request->country);

      
        $transactionRequestType->setBillTo($billingAddress);



        $controller = new AnetController\CreateTransactionController($apiRequest);

        // $response = $controller->executeWithApiResponse(\net\authorize\api\constants\ANetEnvironment::SANDBOX);
        $response = $controller->executeWithApiResponse(\net\authorize\api\constants\ANetEnvironment::PRODUCTION);
        
        if ($response) {
            $transactionResponse = $response->getTransactionResponse();

            if ($transactionResponse && $transactionResponse->getResponseCode() == "1") {

                $user = User::create([
                    'first_name' => $request->first_name,
                    'last_name' => $request->last_name,
                    'email' => $request->email,
                    'paid' => 'Yes',
                    'status' => 'pending',
                ]);

                Subscription::create([
                    'user_id' => $user->id,
                    'plan_id' => $request->plan_id,
                    'subscription_type' => $request->type,
                    'subscription_amount' => $request->amount,
                    'start_date' => now(),
                    'renewal_date' => $request->type === 'Monthly' ? now()->addMonth() : now()->addYear(),
                    'status' => 'active',
                    'transaction_id' => $transactionResponse->getTransId(),
                ]);

                $token = Str::random(64);

                DB::table('password_reset_tokens')->updateOrInsert(
                    ['email' => $request->email],
                    ['token' => $token, 'created_at' => now()]
                );


                Mail::send('emails.password-setup', ['token' => $token], function ($message) use ($request) {
                    $message->to($request->email);
                    $message->subject('Verify Email & Setup Password');
                });


                return back()->with('success', 'Please check your email to verify your account and set up your password.');



            } else {
                if ($transactionResponse && $transactionResponse->getErrors()) {
                    foreach ($transactionResponse->getErrors() as $error) {
                        $errorCode = $error->getErrorCode();
                        $errorMessage = $error->getErrorText();
                        return back()->with('error', "Payment failed: $errorCode - $errorMessage");
                    }
                } elseif ($response && $response->getMessages()) {
                    foreach ($response->getMessages()->getMessage() as $message) {
                        $errorCode = $message->getCode();
                        $errorMessage = $message->getText();
                        return back()->with('error', "Payment failed: $errorCode - $errorMessage");
                    }
                }
            }
        } else {
            $errorMessages = $response->getMessages() && $response->getMessages()->getMessage()
                ? $response->getMessages()->getMessage()[0]->getText()
                : 'No response from the payment gateway.';
            return back()->with('error', 'Payment failed: ' . $errorMessages);
        }
    }
}
