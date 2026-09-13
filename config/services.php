<?php

return [
    'stripe' => [
        'public' => env('STRIPE_PUBLIC_KEY'),
        'secret' => env('STRIPE_SECRET_KEY'),
        'webhook_secret' => env('STRIPE_WEBHOOK_SECRET'),
    ],
    
    'paypal' => [
        'mode' => env('PAYPAL_MODE', 'sandbox'),
        'account_id' => env('PAYPAL_ACCOUNT_ID'),
        'client_id' => env('PAYPAL_CLIENT_ID'),
        'client_secret' => env('PAYPAL_CLIENT_SECRET'),
    ],

    'crypto' => [
        'coinbase_api_key' => env('COINBASE_API_KEY'),
        'coinbase_api_url' => 'https://api.commerce.coinbase.com',
        'supported_coins' => ['BTC', 'ETH', 'USDC', 'DOGE'],
        'confirmations_required' => env('CRYPTO_CONFIRMATIONS', 1),
    ],
];
