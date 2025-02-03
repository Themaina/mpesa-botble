<?php

namespace Botble\Mpesa\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use GuzzleHttp\Client;

class PaymentController extends Controller
{
    /**
     * Retrieve an access token from Daraja.
     *
     * @return string
     * @throws \Exception
     */
    protected function getAccessToken()
    {
        $consumerKey    = config('mpesa.consumer_key');
        $consumerSecret = config('mpesa.consumer_secret');
        $authUrl        = config('mpesa.auth_url');

        $credentials = base64_encode("$consumerKey:$consumerSecret");

        $client = new Client();
        $response = $client->get($authUrl, [
            'headers' => [
                'Authorization' => 'Basic ' . $credentials,
            ],
        ]);

        $body = json_decode($response->getBody());
        if (isset($body->access_token)) {
            return $body->access_token;
        }

        // Handle error appropriately (log, throw exception, etc.)
        throw new \Exception('Could not retrieve access token from Daraja');
    }

    /**
     * Initiate an M-Pesa payment.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function initiatePayment(Request $request)
    {
        // Validate request parameters
        $request->validate([
            'amount' => 'required|numeric',
            'phone'  => 'required',
        ]);

        $accessToken = $this->getAccessToken();

        // Generate timestamp and password per Daraja requirements
        $timestamp = now()->format('YmdHis');
        $businessShortCode = config('mpesa.business_shortcode');
        $passkey = config('mpesa.passkey');
        $password = base64_encode($businessShortCode . $passkey . $timestamp);

        $payload = [
            "BusinessShortCode" => $businessShortCode,
            "Password"          => $password,
            "Timestamp"         => $timestamp,
            "TransactionType"   => "CustomerPayBillOnline",
            "Amount"            => $request->input('amount'),
            "PartyA"            => $request->input('phone'),
            "PartyB"            => $businessShortCode,
            "PhoneNumber"       => $request->input('phone'),
            "CallBackURL"       => config('mpesa.callback_url'),
            "AccountReference"  => "YourCompanyName",
            "TransactionDesc"   => "Payment description"
        ];

        $client = new Client();

        try {
            $response = $client->post(config('mpesa.payment_url'), [
                'headers' => [
                    'Authorization' => 'Bearer ' . $accessToken,
                    'Content-Type'  => 'application/json'
                ],
                'json' => $payload,
            ]);

            $body = json_decode($response->getBody());

            // Optionally, save the transaction details to your database

            return response()->json($body);
        } catch (\Exception $e) {
            // Log the error and return a JSON response with error details
            \Log::error('M-Pesa Payment Error: ' . $e->getMessage());
            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Handle the callback from Daraja.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function callback(Request $request)
    {
        // Log or process the callback data from Daraja
        \Log::info('M-Pesa Callback:', $request->all());

        // Always return a 200 OK response
        return response()->json(['ResultCode' => 0, 'ResultDesc' => 'Accepted']);
    }
}
