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
        Schema::create('incident_document', function (Blueprint $table) {
            $table->id();
            $table->uuid('incident_id')->nullable();
            $table->smallInteger('type')->default(1);
            $table->string('path',100)->nullable(); 

            $table->log();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('incident_document');
    }
};
