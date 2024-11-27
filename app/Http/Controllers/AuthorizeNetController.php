<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
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
        
        
        $validatedData = $request->validate([
            'card_number' => 'required|numeric',
            'expiration_date' => 'required|date_format:m/y',
            'cvv' => 'required|digits:3',
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

        // Create transaction request
        $transactionRequestType = new AnetAPI\TransactionRequestType();
        $transactionRequestType->setTransactionType("authCaptureTransaction");
        $transactionRequestType->setAmount($validatedData['amount']);
        $transactionRequestType->setPayment($payment);

        // Build the API request
        $apiRequest = new AnetAPI\CreateTransactionRequest();
        $apiRequest->setMerchantAuthentication($merchantAuthentication);
        $apiRequest->setRefId("ref" . time());
        $apiRequest->setTransactionRequest($transactionRequestType);

        // Execute the API request
        $controller = new AnetController\CreateTransactionController($apiRequest);
        $response = $controller->executeWithApiResponse(\net\authorize\api\constants\ANetEnvironment::SANDBOX);

        // Handle the API response
        if ($response) {
            $transactionResponse = $response->getTransactionResponse();

            if ($transactionResponse && $transactionResponse->getResponseCode() == "1") {

                

                return back()->with('success', 'Payment successful! Transaction ID: ' . $transactionResponse->getTransId());
            
            
            
            } else {
                $errorMessage = $transactionResponse && $transactionResponse->getErrors()
                    ? $transactionResponse->getErrors()[0]->getErrorText()
                    : 'Unknown error occurred.';
                return back()->with('error', 'Payment failed: ' . $errorMessage);
            }
        } else {
            $errorMessages = $response->getMessages() && $response->getMessages()->getMessage()
                ? $response->getMessages()->getMessage()[0]->getText()
                : 'No response from the payment gateway.';
            return back()->with('error', 'Payment failed: ' . $errorMessages);
        }
    }
}
