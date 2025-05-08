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
        Schema::create('sla_category', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('sla_id')->nullable(); 
            $table->unsignedBigInteger('category_id')->nullable(); 
            $table->log();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sla_category');

    }
};
