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
        Schema::create('year_groups', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('start_year');
            $table->integer('end_year');
            $table->text('description')->nullable();
            $table->string('whatsapp_link')->nullable();
            $table->string('telegram_link')->nullable();
            $table->string('gekychat_link')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            // Indexes
            $table->index(['start_year', 'end_year']);
            $table->index('is_active');
        });

        // Insert some default year groups
        DB::table('year_groups')->insert([
            [
                'name' => 'Pioneers (1968-1979)',
                'start_year' => 1968,
                'end_year' => 1979,
                'description' => 'Our founding alumni - the pioneers of STU',
                'whatsapp_link' => '',
                'telegram_link' => '',
                'gekychat_link' => '',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'The 80s Generation',
                'start_year' => 1980,
                'end_year' => 1989,
                'description' => 'Alumni from the transformative 1980s',
                'whatsapp_link' => '',
                'telegram_link' => '',
                'gekychat_link' => '',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'The 90s Generation',
                'start_year' => 1990,
                'end_year' => 1999,
                'description' => 'Alumni from the 1990s',
                'whatsapp_link' => '',
                'telegram_link' => '',
                'gekychat_link' => '',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Millennium Generation (2000-2009)',
                'start_year' => 2000,
                'end_year' => 2009,
                'description' => 'Alumni from the new millennium',
                'whatsapp_link' => '',
                'telegram_link' => '',
                'gekychat_link' => '',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'The 2010s Cohort',
                'start_year' => 2010,
                'end_year' => 2019,
                'description' => 'Alumni from 2010-2019',
                'whatsapp_link' => '',
                'telegram_link' => '',
                'gekychat_link' => '',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Recent Graduates (2020+)',
                'start_year' => 2020,
                'end_year' => 2030,
                'description' => 'Our newest alumni',
                'whatsapp_link' => '',
                'telegram_link' => '',
                'gekychat_link' => '',
                'is_active' => true,
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
        Schema::dropIfExists('year_groups');
    }
};

