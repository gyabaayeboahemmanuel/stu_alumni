<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('sis_integrations', function (Blueprint $table) {
            $table->id();
            $table->string('student_id')->unique();
            $table->json('request_data')->nullable();
            $table->json('response_data')->nullable();
            $table->enum('status', ['pending', 'success', 'failed', 'not_found'])->default('pending');
            $table->timestamp('verified_at')->nullable();
            $table->text('error_message')->nullable();
            $table->timestamps();

            $table->index('status');
            $table->index('verified_at');
        });
    }

    public function down()
    {
        Schema::dropIfExists('sis_integrations');
    }
};
