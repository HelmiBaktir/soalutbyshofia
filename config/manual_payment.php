<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Pembayaran Manual (Transfer Bank / QRIS)
    |--------------------------------------------------------------------------
    |
    | Data rekening / QRIS untuk pembayaran manual. Isi nilai yang sebenarnya
    | di file .env (MANUAL_BANK_NAME, MANUAL_ACCOUNT_NUMBER, dst).
    |
    */

    'bank_name' => env('MANUAL_BANK_NAME', 'Bank BCA'),

    'account_number' => env('MANUAL_ACCOUNT_NUMBER', ''),

    'account_holder' => env('MANUAL_ACCOUNT_HOLDER', 'Shofia'),

    // Path relatif dari folder public/ (contoh: assets/qris.png)
    'qris_image' => env('MANUAL_QRIS_IMAGE'),

];
