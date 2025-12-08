<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description');
            $table->timestamp('event_date');
            $table->timestamp('event_end_date')->nullable();
            $table->string('venue')->nullable();
            $table->string('online_link')->nullable();
            $table->enum('event_type', ['physical', 'online', 'hybrid'])->default('physical');
            $table->integer('max_attendees')->nullable();
            $table->string('featured_image')->nullable();
            $table->boolean('is_published')->default(false);
            $table->boolean('is_featured')->default(false);
            $table->timestamp('registration_deadline')->nullable();
            $table->boolean('requires_approval')->default(false);
            $table->decimal('price', 8, 2)->default(0);
            $table->string('currency')->default('GHS');
            $table->timestamps();
            $table->softDeletes();

            $table->index(['is_published', 'event_date']);
            $table->index('is_featured');
        });
    }

    public function down()
    {
        Schema::dropIfExists('events');
    }
};
