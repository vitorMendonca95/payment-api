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

    'current_payment_authorization_company' => env('CURRENT_PAYMENT_AUTHORIZATION_COMPANY', 'companyX'),

    'payment_authorization_companies' => [
        'company_x' => [
            'base_url' => env('COMPANY_X_BASE_URL', 'https://util.devi.tools'),
            'authorize_path' => env('COMPANY_X_AUTHORIZE_PATH', '/api/v2/authorize'),
        ],
    ],

    'notification_company' => [
        'base_url' => env('NOTIFICATION_COMPANY_BASE_URL', 'https://util.devi.tools'),
        'notify_path' => env('NOTIFICATION_COMPANY_AUTHORIZE_PATH', '/api/v1/notify'),
    ],

];
