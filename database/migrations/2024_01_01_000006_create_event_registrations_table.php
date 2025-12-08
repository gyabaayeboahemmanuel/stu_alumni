<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('event_registrations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained()->onDelete('cascade');
            $table->foreignId('alumni_id')->constrained('alumni')->onDelete('cascade');
            $table->timestamp('registration_date')->useCurrent();
            $table->enum('status', ['pending', 'confirmed', 'cancelled', 'waitlisted'])->default('pending');
            $table->text('notes')->nullable();
            $table->boolean('attended')->default(false);
            $table->timestamp('attended_at')->nullable();
            $table->timestamps();

            $table->unique(['event_id', 'alumni_id']);
            $table->index(['event_id', 'status']);
            $table->index('registration_date');
        });
    }

    public function down()
    {
        Schema::dropIfExists('event_registrations');
    }
};
