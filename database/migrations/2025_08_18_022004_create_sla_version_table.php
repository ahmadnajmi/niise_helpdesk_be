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
        Schema::create('sla_version', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('sla_template_id')->nullable(); 
            $table->integer('version');
            $table->string('response_time')->nullable();
            $table->smallInteger('response_time_type')->nullable();
            $table->string('response_time_penalty',20)->nullable();
            $table->string('resolution_time')->nullable();
            $table->smallInteger('resolution_time_type')->nullable();
            $table->string('resolution_time_penalty',20)->nullable();

            $table->log();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sla_version');
    }
};
