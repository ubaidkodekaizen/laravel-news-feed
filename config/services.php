<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'resend' => [
        'key' => env('RESEND_KEY'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    'google_play' => [
        'package_name' => env('GOOGLE_PLAY_PACKAGE_NAME'),
        'credentials_path' => env('GOOGLE_PLAY_CREDENTIALS_PATH', storage_path('app/google/google-play-service-account.json')),
        'products' => [
            'Premium_Monthly' => env('GOOGLE_PLAY_PREMIUM_MONTHLY_PRODUCT_ID'),
            'Premium_Yearly' => env('GOOGLE_PLAY_PREMIUM_YEARLY_PRODUCT_ID'),
        ],
    ],

    'muslimlynk' => [
        'api_url' => env('MUSLIMLYNK_API_URL', 'https://muslimlynk.kodereach.com'),
        'api_key' => env('MUSLIMLYNK_API_KEY'), // API key for accessing the system (used in ApiKeyMiddleware)
        'api_key_ai' => env('MUSLIMLYNK_API_KEY_AI'), // API key for accessing external AI API
    ],

     'firebase' => [
        'credentials' => env('FIREBASE_CREDENTIALS', storage_path('app/firebase-credentials.json')),
        'database_url' => env('FIREBASE_DATABASE_URL'),
        'project_id' => env('FIREBASE_PROJECT_ID'),
    ],

];
