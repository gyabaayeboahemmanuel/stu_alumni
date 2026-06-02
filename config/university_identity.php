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

    'base_url' => env('UNIVERSITY_IDENTITY_BASE_URL', 'https://identity.stu.edu.gh'),

    'get_alumni_path' => env('UNIVERSITY_IDENTITY_GET_ALUMNI_PATH', '/getAlumni.php'),

    // Uses config('app.remote_secret') / CODE — same token as student verification

    'timeout' => (int) env('UNIVERSITY_IDENTITY_TIMEOUT', 60),
];

