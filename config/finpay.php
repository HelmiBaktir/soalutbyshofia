<?php

return [

    /*
    |--------------------------------------------------------------------------
    | FinPay Base URL
    |--------------------------------------------------------------------------
    | Base URL API FinPay (production/sandbox). Contoh: https://api.finpay.co.id
    */
    'base_url' => env('FINPAY_BASE_URL', 'https://api.finpay.co.id'),

    /*
    |--------------------------------------------------------------------------
    | Merchant Credentials
    |--------------------------------------------------------------------------
    */
    'merchant_id' => env('FINPAY_MERCHANT_ID'),
    'merchant_key' => env('FINPAY_MERCHANT_KEY'),
    'merchant_secret' => env('FINPAY_MERCHANT_SECRET'),

    /*
    |--------------------------------------------------------------------------
    | URLs
    |--------------------------------------------------------------------------
    */
    'redirect_url' => env('FINPAY_REDIRECT_URL'),
    'callback_url' => env('FINPAY_CALLBACK_URL'),

    /*
    |--------------------------------------------------------------------------
    | Endpoints
    |--------------------------------------------------------------------------
    | TODO: sesuaikan path endpoint dengan dokumentasi resmi FinPay.
    */
    'payment_endpoint' => env('FINPAY_PAYMENT_ENDPOINT', '/payment'),
    'status_endpoint' => env('FINPAY_STATUS_ENDPOINT', '/status'),

    /*
    |--------------------------------------------------------------------------
    | HTTP Timeout (detik)
    |--------------------------------------------------------------------------
    */
    'timeout' => (int) env('FINPAY_TIMEOUT', 30),
];
