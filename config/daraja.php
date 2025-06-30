<?php

return [
    'env' => env('DARAJA_ENV', 'sandbox'),
    'consumer_key' => env('DARAJA_CONSUMER_KEY'),
    'consumer_secret' => env('DARAJA_CONSUMER_SECRET'),
    'shortcode' => env('DARAJA_SHORTCODE'),
    'passkey' => env('DARAJA_PASSKEY'),
    'b2c_initiator' => env('DARAJA_B2C_INITIATOR'),
    'initiator_password' => env('DARAJA_INITIATOR_PASSWORD'),
    'base_url' => env('DARAJA_ENV', 'sandbox') === 'sandbox'
        ? 'https://sandbox.safaricom.co.ke'
        : 'https://api.safaricom.co.ke',
    'b2c_timeout_url' => env('DARAJA_B2C_TIMEOUT_URL'),
    'b2c_result_url' => env('DARAJA_B2C_RESULT_URL'),
    'confirmation_url' => env('DARAJA_CONFIRMATION_URL'),
    'validation_url' => env('DARAJA_VALIDATION_URL'),
];
