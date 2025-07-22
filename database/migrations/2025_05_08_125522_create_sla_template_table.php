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
        Schema::create('sla_template', function (Blueprint $table) {
            $table->id();
            $table->string('code',50)->nullable();
            $table->smallInteger('severity_id');
            $table->string('service_level',50)->nullable();
            $table->string('timeframe_channeling')->nullable();
            $table->smallInteger('timeframe_channeling_type')->nullable();
            $table->string('timeframe_incident')->nullable();
            $table->smallInteger('timeframe_incident_type')->nullable();

            $table->string('response_time_reply')->nullable();
            $table->smallInteger('response_time_reply_type')->nullable();
            $table->smallInteger('response_time_reply_penalty')->nullable();

            $table->string('timeframe_solution')->nullable();
            $table->smallInteger('timeframe_solution_type')->nullable();
            $table->smallInteger('timeframe_solution_penalty')->nullable();

            $table->string('response_time_location')->nullable();
            $table->smallInteger('response_time_location_type')->nullable();
            $table->smallInteger('response_time_location_penalty')->nullable();


            $table->string('notes')->nullable();

            $table->log();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sla_template');
    }
};
