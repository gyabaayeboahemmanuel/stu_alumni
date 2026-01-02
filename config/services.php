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

    'arkesel' => [
        'base_url' => env('ARKESEL_BASE_URL', 'https://sms.arkesel.com/api/v2'),
        'api_key'  => env('ARKESEL_SMS_API_KEY'),
        'sender'   => env('ARKESEL_SMS_SENDER', 'STU Alumni'),
        'sender_id' => env('ARKESEL_SENDER_ID', 'STUAlumni'), // fallback
        'sandbox'  => (bool) env('ARKESEL_SMS_SANDBOX', false),
    ],

    'whatsapp' => [
        // Generic WhatsApp API (for third-party providers)
        'api_key'  => env('WHATSAPP_API_KEY'),
        'api_url'  => env('WHATSAPP_API_URL'),
        
        // WhatsApp Cloud API (Meta/Facebook) - CUG style
        'token'               => env('WHATSAPP_TOKEN'),
        'phone_number_id'     => env('WHATSAPP_PHONE_NUMBER_ID'),
        'business_account_id' => env('WHATSAPP_BUSINESS_ACCOUNT_ID'),
        'waba_id'             => env('WHATSAPP_WABA_ID'),
        'api_version'         => env('WHATSAPP_API_VERSION', 'v21.0'),
        'api_base'            => env('WHATSAPP_API_BASE', 'https://graph.facebook.com'),
        'verify_token'        => env('WHATSAPP_VERIFY_TOKEN'),
        'app_secret'          => env('WHATSAPP_APP_SECRET'),
        'timeout'             => (int) env('WHATSAPP_HTTP_TIMEOUT', 30),
        
        // Template settings (REQUIRED for Meta Cloud API)
        'default_template'    => env('WHATSAPP_DEFAULT_TEMPLATE'), // Template name to use for all messages
        'template_locale'     => env('WHATSAPP_TEMPLATE_LOCALE', 'en_US'), // Template language code
        'default_template_params_count' => (int) env('WHATSAPP_DEFAULT_TEMPLATE_PARAMS_COUNT', 1), // Number of body variables in template (0 = no variables, 1 = one variable {{1}}, etc.)
    ],

    'gekychat' => [
        // Platform API is on api subdomain, not chat subdomain
        // Routes are at: api.gekychat.test/platform/oauth/token
        // So base_url should be just the domain (no /api prefix)
        'base_url' => env('GEKYCHAT_API_URL', env('APP_ENV') === 'local' ? 'http://api.gekychat.test' : 'https://api.gekychat.com'),
        'client_id' => env('GEKYCHAT_CLIENT_ID'),
        'client_secret' => env('GEKYCHAT_CLIENT_SECRET'),
        'system_bot_user_id' => (int) env('GEKYCHAT_SYSTEM_BOT_USER_ID', 0),
    ],

];
