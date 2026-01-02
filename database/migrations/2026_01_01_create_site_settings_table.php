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
        Schema::create('site_settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->string('type')->default('text'); // text, url, email, phone, textarea, image
            $table->string('group')->default('general'); // social_media, contact, general
            $table->text('description')->nullable();
            $table->timestamps();
        });

        // Insert default social media settings
        DB::table('site_settings')->insert([
            [
                'key' => 'facebook_url',
                'value' => 'https://facebook.com/stualumni',
                'type' => 'url',
                'group' => 'social_media',
                'description' => 'Facebook Page URL',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'twitter_url',
                'value' => 'https://twitter.com/stualumni',
                'type' => 'url',
                'group' => 'social_media',
                'description' => 'Twitter/X Profile URL',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'linkedin_url',
                'value' => 'https://linkedin.com/company/stu-alumni',
                'type' => 'url',
                'group' => 'social_media',
                'description' => 'LinkedIn Page URL',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'instagram_url',
                'value' => 'https://instagram.com/stualumni',
                'type' => 'url',
                'group' => 'social_media',
                'description' => 'Instagram Profile URL',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'youtube_url',
                'value' => '',
                'type' => 'url',
                'group' => 'social_media',
                'description' => 'YouTube Channel URL (Optional)',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'contact_email',
                'value' => 'alumni@stu.edu.gh',
                'type' => 'email',
                'group' => 'contact',
                'description' => 'Contact Email Address',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'contact_phone',
                'value' => '+233 (0) 35 209 1234',
                'type' => 'phone',
                'group' => 'contact',
                'description' => 'Contact Phone Number',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'contact_address',
                'value' => 'Alumni Office, Sunyani Technical University',
                'type' => 'textarea',
                'group' => 'contact',
                'description' => 'Physical Address',
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
        Schema::dropIfExists('site_settings');
    }
};

