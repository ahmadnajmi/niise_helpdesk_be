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
            $table->smallInteger('service_level');
            $table->string('timeframe_channeling')->nullable();
            $table->string('timeframe_channeling_type')->nullable();
            $table->string('timeframe_incident')->nullable();
            $table->string('timeframe_incident_type')->nullable();
            $table->string('response_time_reply')->nullable();
            $table->string('response_time_reply_type')->nullable();
            $table->string('timeframe_solution')->nullable();
            $table->string('timeframe_solution_type')->nullable();
            $table->string('response_time_location')->nullable();
            $table->string('response_time_location_type')->nullable();
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
