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
        Schema::dropIfExists('donations');
        
        Schema::create('donations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('alumni_id')->nullable()->constrained('alumni')->onDelete('set null');
            $table->enum('type', ['cash', 'in_kind'])->default('cash');
            $table->text('description')->nullable(); // For in-kind donations
            $table->text('items')->nullable(); // For in-kind donations - list of items
            $table->string('country')->nullable(); // For in-kind donations
            $table->string('city')->nullable(); // For in-kind donations
            $table->string('contact')->nullable(); // For in-kind donations - phone/email
            $table->string('status')->default('pending'); // pending, approved, rejected
            $table->text('admin_notes')->nullable();
            $table->timestamp('processed_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('donations');
    }
};
