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
        Schema::create('operating_times', function (Blueprint $table) {
            $table->id();            
            $table->unsignedBigInteger('branch_id')->nullable(); 
            $table->smallInteger('day_start')->nullable();
            $table->smallInteger('day_end')->nullable();
            $table->smallInteger('duration')->nullable();
            $table->dateTime('operation_start')->nullable();
            $table->dateTime('operation_end')->nullable();
            $table->boolean('is_active')->default(true);
            $table->log();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('operating_times');
    }
};
