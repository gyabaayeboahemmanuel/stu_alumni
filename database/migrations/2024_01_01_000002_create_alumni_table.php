<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('alumni', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('student_id')->unique()->nullable();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('other_names')->nullable();
            $table->string('email')->unique();
            $table->string('phone')->nullable();
            $table->enum('gender', ['male', 'female', 'other'])->nullable();
            $table->date('date_of_birth')->nullable();
            $table->year('year_of_completion');
            $table->string('programme');
            $table->string('qualification')->nullable();
            $table->string('current_employer')->nullable();
            $table->string('job_title')->nullable();
            $table->string('industry')->nullable();
            $table->string('country')->nullable();
            $table->string('city')->nullable();
            $table->text('postal_address')->nullable();
            $table->string('website')->nullable();
            $table->string('linkedin')->nullable();
            $table->string('twitter')->nullable();
            $table->string('facebook')->nullable();
            $table->string('profile_photo_path')->nullable();
            $table->enum('verification_status', ['unverified', 'pending', 'verified', 'rejected'])->default('unverified');
            $table->enum('verification_source', ['sis', 'manual'])->nullable();
            $table->timestamp('verified_at')->nullable();
            $table->boolean('is_visible_in_directory')->default(true);
            $table->enum('registration_method', ['sis', 'manual'])->default('manual');
            $table->string('proof_document_path')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // Indexes for performance
            $table->index(['verification_status', 'year_of_completion']);
            $table->index(['country', 'city']);
            $table->index('programme');
        });
    }

    public function down()
    {
        Schema::dropIfExists('alumni');
    }
};
