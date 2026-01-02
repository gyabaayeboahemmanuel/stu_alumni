<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Insert notification settings as key-value pairs
        DB::table('site_settings')->insert([
            [
                'key' => 'email_notifications_enabled',
                'value' => '1',
                'type' => 'boolean',
                'group' => 'notifications',
                'description' => 'Enable email notifications system-wide',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'sms_notifications_enabled',
                'value' => '0',
                'type' => 'boolean',
                'group' => 'notifications',
                'description' => 'Enable SMS notifications system-wide',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'registration_notification_enabled',
                'value' => '1',
                'type' => 'boolean',
                'group' => 'notifications',
                'description' => 'Send notifications for new registrations',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'event_notification_enabled',
                'value' => '1',
                'type' => 'boolean',
                'group' => 'notifications',
                'description' => 'Send notifications for new events',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'announcement_notification_enabled',
                'value' => '1',
                'type' => 'boolean',
                'group' => 'notifications',
                'description' => 'Send notifications for new announcements',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('site_settings')->whereIn('key', [
            'email_notifications_enabled',
            'sms_notifications_enabled',
            'registration_notification_enabled',
            'event_notification_enabled',
            'announcement_notification_enabled',
        ])->delete();
    }
};

