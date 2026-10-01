<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    */

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'resend' => [
        'key' => env('RESEND_KEY'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Steadfast API Configuration
    |--------------------------------------------------------------------------
    */

    'facebook' => [
        'pixel_id'     => env('FACEBOOK_PIXEL_ID'),
        'access_token' => env('FACEBOOK_ACCESS_TOKEN'),
    ],

    'sms' => [
        'api_key'      => env('SMS_API_KEY'),
        'sender_id'    => env('SMS_SENDER_ID'),
        'api_url'      => env('SMS_API_URL', 'https://sms.mram.com.bd/smsapi'),
        'admin_number' => env('SMS_ADMIN_NUMBER'),
    ],

    'steadfast' => [
        'api_key'    => env('STEADFAST_API_KEY'),
        'secret_key' => env('STEADFAST_SECRET_KEY'),
        'base_url'   => env('STEADFAST_BASE_URL', 'https://portal.packzy.com/api/v1'),
    ],

];