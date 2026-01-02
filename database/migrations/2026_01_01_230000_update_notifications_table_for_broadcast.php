<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Update type enum to include 'broadcast'
        DB::statement("ALTER TABLE notifications MODIFY COLUMN type ENUM('registration', 'verification', 'event', 'newsletter', 'broadcast') NOT NULL");
        
        // Update sent_via enum to include 'whatsapp', 'gekychat', or change to VARCHAR for flexibility
        // Using VARCHAR to allow comma-separated values like 'email,sms,whatsapp'
        DB::statement("ALTER TABLE notifications MODIFY COLUMN sent_via VARCHAR(255) DEFAULT 'email'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert sent_via back to enum
        DB::statement("ALTER TABLE notifications MODIFY COLUMN sent_via ENUM('email', 'sms') DEFAULT 'email'");
        
        // Revert type enum (remove broadcast)
        DB::statement("ALTER TABLE notifications MODIFY COLUMN type ENUM('registration', 'verification', 'event', 'newsletter') NOT NULL");
    }
};

