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
        Schema::create('ref_table', function (Blueprint $table) {
            $table->id();
            $table->string('code_category')->nullable();
            $table->string('ref_code')->nullable();
            $table->string('name')->nullable();
            $table->string('name_en')->nullable();
            $table->log();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ref_table');
    }
};
