<?php

return [
    /*
    |--------------------------------------------------------------------------
    | SIS Integration Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration for Student Information System integration
    | Set enabled to false for development without actual SIS connection
    |
    */

    'enabled' => env('SIS_ENABLED', true),
    
    'base_url' => env('REMOTE', 'https://stu.edu.gh/identity/verify_connect_api'),
    
    'api_key' => env('CODE', 'DEV1'),
    
    'timeout' => 30,
    
    'retry_attempts' => 3,
    
    'cache_duration' => 3600, // 1 hour
    
    'endpoints' => [
        'verify_alumni' => '/verify-alumni',
        'get_alumni_data' => '/alumni/{student_id}',
        'batch_verify' => '/batch-verify',
    ],
    
    'mock_data' => [
        'enabled' => env('APP_DEBUG', false),
        'response_delay' => 1, // seconds
    ],
];