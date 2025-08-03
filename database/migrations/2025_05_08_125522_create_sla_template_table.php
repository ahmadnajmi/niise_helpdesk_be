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
            $table->unsignedBigInteger('company_id')->nullable();
            $table->unsignedBigInteger('company_contract_id')->nullable();


            $table->string('response_time')->nullable();
            $table->smallInteger('response_time_type')->nullable();
            $table->string('response_time_penalty',20)->nullable();

            $table->string('resolution_time')->nullable();
            $table->smallInteger('resolution_time_type')->nullable();
            $table->string('resolution_time_penalty',20)->nullable();

            $table->string('response_time_location')->nullable();
            $table->smallInteger('response_time_location_type')->nullable();
            $table->string('response_time_location_penalty',20)->nullable();

            $table->string('temporary_resolution_time')->nullable();
            $table->smallInteger('temporary_resolution_time_type')->nullable();
            $table->string('temporary_resolution_time_penalty',20)->nullable();

            $table->string('dispatch_time')->nullable();
            $table->smallInteger('dispatch_time_type')->nullable();

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
