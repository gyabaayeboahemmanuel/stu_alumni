<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('executives', function (Blueprint $table) {
            $table->id();
            $table->foreignId('alumni_id')->constrained('alumni')->onDelete('cascade');
            $table->string('position');
            $table->string('term_year');
            $table->date('start_date');
            $table->date('end_date');
            $table->boolean('is_current')->default(false);
            $table->integer('display_order')->default(0);
            $table->text('bio')->nullable();
            $table->json('achievements')->nullable();
            $table->timestamps();

            $table->index(['term_year', 'position']);
            $table->index('is_current');
            $table->index('display_order');
        });
    }

    public function down()
    {
        Schema::dropIfExists('executives');
    }
};
