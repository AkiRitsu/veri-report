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

    'mailjet' => [
        'api_key' => env('MAILJET_API_KEY'),
        'api_secret' => env('MAILJET_API_SECRET'),
        'from_email' => env('MAILJET_FROM_EMAIL'),
        'from_name' => env('MAILJET_FROM_NAME'),
    ],

];

