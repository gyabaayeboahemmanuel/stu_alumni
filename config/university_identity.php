<?php

return [
    /*
    |--------------------------------------------------------------------------
    | University Identity (SIS-like) Integration
    |--------------------------------------------------------------------------
    |
    | Used for calling STU identity endpoints like /getAlumni.
    | Defaults are set to match the current production endpoint.
    |
    */

    'base_url' => env('UNIVERSITY_IDENTITY_BASE_URL', 'https://www.stu.edu.gh/identity'),

    // Default to SIS_API_KEY for consistency with SIS verification setup
    'api_key' => env('UNIVERSITY_IDENTITY_API_KEY', env('SIS_API_KEY', '')),

    // Typical format: "Bearer <key>". Can be changed if the university requires a different scheme.
    'auth_scheme' => env('UNIVERSITY_IDENTITY_AUTH_SCHEME', 'Bearer'),

    'timeout' => (int) env('UNIVERSITY_IDENTITY_TIMEOUT', 60),
];

