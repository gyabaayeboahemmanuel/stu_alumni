<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Alumni System Configuration
    |--------------------------------------------------------------------------
    |
    | General configuration for the STU Alumni System
    |
    */

    'version' => '1.0.0',
    
    'features' => [
        'business_directory' => env('FEATURE_BUSINESS_DIRECTORY', true),
        'events' => env('FEATURE_EVENTS', true),
        'announcements' => env('FEATURE_ANNOUNCEMENTS', true),
        'executives' => env('FEATURE_EXECUTIVES', true),
        'sis_integration' => env('FEATURE_SIS_INTEGRATION', true),
    ],

    'registration' => [
        'sis_start_year' => 2014,
        'manual_max_year' => 2013,
        'auto_verify_sis' => true,
        'require_document_manual' => true,
        'document_max_size' => 5120, // 5MB in KB
        'document_types' => ['pdf', 'jpg', 'jpeg', 'png'],
    ],

    'verification' => [
        'statuses' => ['unverified', 'pending', 'verified', 'rejected'],
        'sources' => ['sis', 'manual'],
        'auto_approve_days' => 3, // Auto-approve after 3 days for testing
    ],

    'business' => [
        'auto_approve' => false,
        'featured_limit' => 6,
        'categories' => [
            'Technology', 'Healthcare', 'Education', 'Finance', 'Retail',
            'Manufacturing', 'Services', 'Agriculture', 'Construction', 'Transportation'
        ],
    ],

    'events' => [
        'registration_deadline_before_event' => 24, // hours
        'max_attendees_default' => 100,
        'reminder_hours_before' => 24,
    ],

    'notifications' => [
        'channels' => ['mail', 'database'],
        'queue_notifications' => true,
    ],

    'uploads' => [
        'max_file_size' => env('UPLOAD_MAX_FILESIZE', 5120),
        'allowed_extensions' => explode(',', env('UPLOAD_ALLOWED_EXTENSIONS', 'jpg,jpeg,png,pdf')),
        'paths' => [
            'profile_photos' => 'profile-photos',
            'business_logos' => 'business-logos',
            'proof_documents' => 'proofs',
            'announcement_images' => 'announcements',
            'event_images' => 'events',
        ],
    ],

    'admin' => [
        'emails' => [
            'admin' => env('ADMIN_EMAIL', 'admin@stu.edu.gh'),
            'super_admin' => env('SUPER_ADMIN_EMAIL', 'it.directorate@stu.edu.gh'),
        ],
        'pagination' => [
            'default' => 20,
            'alumni' => 20,
            'businesses' => 12,
            'events' => 10,
            'announcements' => 10,
        ],
    ],

    'api' => [
        'rate_limit' => 60, // requests per minute
        'throttle' => '60,1',
        'version' => 'v1',
    ],
];
