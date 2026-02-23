<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('chapters', function (Blueprint $table) {
            $table->string('telegram_link')->nullable()->after('whatsapp_link');
            $table->string('gekychat_link')->nullable()->after('telegram_link');
        });
    }

    public function down(): void
    {
        Schema::table('chapters', function (Blueprint $table) {
            $table->dropColumn(['telegram_link', 'gekychat_link']);
        });
    }
};
