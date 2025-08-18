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
        Schema::create('incident_penalty', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('incident_id')->nullable(); 
            $table->string('total_response_time_penalty_price',20)->nullable();
            $table->string('total_response_time_penalty_minute',20)->nullable();

            $table->log();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('incident_penalty');
    }
};
