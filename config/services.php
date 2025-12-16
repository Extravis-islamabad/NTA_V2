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
        'key' => env('POSTMARK_API_KEY'),
    ],

    'resend' => [
        'key' => env('RESEND_API_KEY'),
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
    | MaxMind GeoIP Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration for MaxMind GeoLite2 databases used for IP geolocation.
    | Sign up for a free account at https://www.maxmind.com/en/geolite2/signup
    | to get your license key.
    |
    */
    'maxmind' => [
        'license_key' => env('MAXMIND_LICENSE_KEY'),
        'account_id' => env('MAXMIND_ACCOUNT_ID'),
        'database_path' => storage_path('app/geoip'),
        'city_db' => 'GeoLite2-City.mmdb',
        'asn_db' => 'GeoLite2-ASN.mmdb',
        'cache_ttl' => env('GEOIP_CACHE_TTL', 2592000), // 30 days
    ],

];
