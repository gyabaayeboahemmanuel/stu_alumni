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
        Schema::create('chapters', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('region')->nullable();
            $table->string('city')->nullable();
            $table->string('country')->default('Ghana');
            $table->text('description')->nullable();
            $table->foreignId('president_id')->nullable()->constrained('alumni')->onDelete('set null');
            $table->string('contact_email')->nullable();
            $table->string('contact_phone')->nullable();
            $table->text('meeting_location')->nullable();
            $table->string('whatsapp_link')->nullable();
            $table->boolean('is_active')->default(true);
            $table->boolean('is_approved')->default(false);
            $table->timestamps();

            // Indexes
            $table->index('region');
            $table->index('city');
            $table->index(['is_active', 'is_approved']);
        });

        // Add chapter_id to alumni table
        Schema::table('alumni', function (Blueprint $table) {
            $table->foreignId('chapter_id')->nullable()->after('verification_status')->constrained('chapters')->onDelete('set null');
        });

        // Insert some default chapters
        DB::table('chapters')->insert([
            [
                'name' => 'Accra Chapter',
                'region' => 'Greater Accra',
                'city' => 'Accra',
                'country' => 'Ghana',
                'description' => 'Alumni chapter for those in the Greater Accra region',
                'president_id' => null,
                'is_active' => true,
                'is_approved' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Kumasi Chapter',
                'region' => 'Ashanti',
                'city' => 'Kumasi',
                'country' => 'Ghana',
                'description' => 'Alumni chapter for those in the Ashanti region',
                'president_id' => null,
                'is_active' => true,
                'is_approved' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Sunyani Chapter',
                'region' => 'Bono',
                'city' => 'Sunyani',
                'country' => 'Ghana',
                'description' => 'Alumni chapter for those in the Bono region (home base)',
                'president_id' => null,
                'is_active' => true,
                'is_approved' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Takoradi Chapter',
                'region' => 'Western',
                'city' => 'Takoradi',
                'country' => 'Ghana',
                'description' => 'Alumni chapter for those in the Western region',
                'president_id' => null,
                'is_active' => true,
                'is_approved' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Tamale Chapter',
                'region' => 'Northern',
                'city' => 'Tamale',
                'country' => 'Ghana',
                'description' => 'Alumni chapter for those in the Northern region',
                'president_id' => null,
                'is_active' => true,
                'is_approved' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'International Chapter',
                'region' => 'International',
                'city' => null,
                'country' => 'Various',
                'description' => 'For alumni residing outside Ghana',
                'president_id' => null,
                'is_active' => true,
                'is_approved' => true,
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
        Schema::table('alumni', function (Blueprint $table) {
            $table->dropForeign(['chapter_id']);
            $table->dropColumn('chapter_id');
        });
        
        Schema::dropIfExists('chapters');
    }
};

