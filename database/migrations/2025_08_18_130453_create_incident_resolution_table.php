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
        Schema::create('incident_resolution', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('incident_id')->nullable(); 
            $table->unsignedBigInteger('group_id')->nullable(); 
            $table->unsignedBigInteger('operation_user_id')->nullable(); 
            $table->string('report_contractor_no',100)->nullable(); 
            $table->string('action_codes',20)->nullable();
            $table->string('notes')->nullable();
            $table->string('solution_notes')->nullable();
            $table->log();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('incident_resolution');
    }
};
