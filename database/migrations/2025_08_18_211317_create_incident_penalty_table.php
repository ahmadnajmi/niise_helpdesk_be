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
            $table->uuid('incident_id')->nullable(); 
            $table->decimal('penalty_irt',10,2)->nullable();
            $table->decimal('penalty_ort',10,2)->nullable();
            $table->decimal('penalty_prt',10,2)->nullable();
            $table->decimal('penalty_vprt',10,2)->nullable();
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
