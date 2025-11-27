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

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
        'scheme' => 'https',
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],
    'google' => [
        'client_id' => env('GOOGLE_CLIENT_ID'),
        'client_secret' => env('GOOGLE_CLIENT_SECRET'),
        'redirect' => config('app_url'). '/auth/google/callback',
    ],
    'sms' => [
        // Which SMS provider to use: 'semaphore', 'twilio', etc.
        'default_provider' => env('SMS_PROVIDER', 'semaphore'),

        // Enable/disable rate limiting (set to false to disable)
        'rate_limit_enabled' => env('SMS_RATE_LIMIT_ENABLED', false),
        'rate_limit_per_hour' => env('SMS_RATE_LIMIT_PER_HOUR', 5),

        // Enable/disable auto-blacklist (set to false to disable)
        'blacklist_enabled' => env('SMS_BLACKLIST_ENABLED', false),
        'blacklist_threshold' => env('SMS_BLACKLIST_THRESHOLD', 10),
        'blacklist_period_days' => env('SMS_BLACKLIST_PERIOD_DAYS', 30),
    ],

    'semaphore' => [
        'api_key' => env('SEMAPHORE_API_KEY'),
        'sender_name' => env('SEMAPHORE_SENDER_NAME', 'HIMS'),
    ],

    'twilio' => [
        'account_sid' => env('TWILIO_ACCOUNT_SID'),
        'auth_token' => env('TWILIO_AUTH_TOKEN'),
        'from_number' => env('TWILIO_FROM_NUMBER'),
    ],
];
