<?php

return [
    'consumer_key'       => env('MPESA_CONSUMER_KEY', 'your_consumer_key'),
    'consumer_secret'    => env('MPESA_CONSUMER_SECRET', 'your_consumer_secret'),
    'business_shortcode' => env('MPESA_BUSINESS_SHORTCODE', 'your_shortcode'),
    'passkey'            => env('MPESA_PASSKEY', 'your_passkey'),
    // Daraja endpoint URLs – update these for production when ready.
    'auth_url'           => env('MPESA_AUTH_URL', 'https://sandbox.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials'),
    'payment_url'        => env('MPESA_PAYMENT_URL', 'https://sandbox.safaricom.co.ke/mpesa/stkpush/v1/processrequest'),
    'callback_url'       => env('MPESA_CALLBACK_URL', 'https://yourdomain.com/mpesa/callback'),
];
