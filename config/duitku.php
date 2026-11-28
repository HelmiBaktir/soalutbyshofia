<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Duitku Payment Gateway
    |--------------------------------------------------------------------------
    |
    | Kredensial & konfigurasi Duitku. Untuk production, ganti
    | DUITKU_BASE_URL ke https://passport.duitku.com/webapi/api/merchant
    | dan pakai Merchant Code + API Key production.
    |
    */

    'merchant_code' => env('DUITKU_MERCHANT_CODE'),

    'api_key' => env('DUITKU_API_KEY'),

    // Sandbox default. Production: https://passport.duitku.com/webapi/api/merchant
    'base_url' => env('DUITKU_BASE_URL', 'https://sandbox.duitku.com/webapi/api/merchant'),

    'callback_url' => env('DUITKU_CALLBACK_URL'),

    'return_url' => env('DUITKU_RETURN_URL'),

];
