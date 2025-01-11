<?php

return [
    'name' => 'ChannelManager',

    /*
    |--------------------------------------------------------------------------
    | Channels Configuration
    |--------------------------------------------------------------------------
    |
    | This option defines the default authentication "guard" and password
    | reset "broker" for your application. You may change these values
    | as required, but they're a perfect start for most applications.
    |
    */

    'airbnb' => [
        'api_key' => env('BOOKING_COM_API_KEY'),
        'api_url' => 'https://api.airbnb.com/v2',
    ],
    'bookingcom' => [
        'api_key' => env('BOOKING_COM_API_KEY'),
        'api_url' => env('BOOKING_COM_API_URL'),
    ],
    'hotelads' => [
        'api_key' => env('BOOKING_COM_API_KEY'),
        'api_url' => 'https://hotelads.googleapis.com',
    ],
];
