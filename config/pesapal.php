<?php

return [
    'consumer_key' => env('PESAPAL_CONSUMER_KEY'),
    'consumer_secret' => env('PESAPAL_CONSUMER_SECRET'),
    'callback_url' => env('PESAPAL_CALLBACK_URL', '/pesapal/callback'),
    'base_url' => env('PESAPAL_BASE_URL', 'https://pay.pesapal.com/v3'),
    'ipn_id' => env('PESAPAL_IPN_ID'), // you'll register this manually (1-time)
];
