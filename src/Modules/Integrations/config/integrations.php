<?php

return [
    'zarinpal' => [
        'merchant_id' => env('ZARINPAL_MERCHANT_ID'),
        'sandbox'     => env('ZARINPAL_SANDBOX', true),
    ],

    'kavenegar' => [
        'api_key' => env('KAVENEGAR_API_KEY'),
        'sender'  => env('KAVENEGAR_SENDER'),
    ],

    'tax_authority' => [
        'token'    => env('TAX_AUTHORITY_TOKEN'),
        'endpoint' => env('TAX_AUTHORITY_ENDPOINT', 'https://tp.tax.gov.ir/req/api/self-tsp'),
    ],
];
