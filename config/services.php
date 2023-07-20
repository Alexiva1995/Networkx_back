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
    'backend_auth' => [
        'key' => env('AUTH_API_KEY'),
        'base_uri' => env('AUTH_URI')
    ],

    'paguelo_facil' => [
        'url' => env('PAGUELO_FACIL_URL_1'),
        'url_2' => env('PAGUELO_FACIL_URL_2'),
        'cclw' => env('PAGUELO_FACIL_CCLW'),
        'access_token' => env('PAGUELO_FACIL_ACCES_TOKEN'),
    ],

    'url_front' => [
        'url' => env('URL_FRONT'),
    ],

    'brokeree' => [
        'url' => env('BROKEREE_HOST', 'https://brokeree-server-placeholder'),
        'username_demo' => env('BROKEREE_USERNAME_DEMO', 12345),
        'password_demo' => env('BROKEREE_PASSWORD_DEMO', 12345),
        'username_real' => env('BROKEREE_USERNAME_REAL', 12345),
        'password_real' => env('BROKEREE_PASSWORD_REAL', 12345),
    ]

];
