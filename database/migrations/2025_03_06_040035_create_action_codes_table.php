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
        Schema::create('action_codes', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->smallInteger('category')->nullable();
            $table->string('abbreviation',20)->nullable();
            $table->string('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->log();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('action_codes');
    }
};
