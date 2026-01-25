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
        Schema::create('report', function (Blueprint $table) {
            $table->id();
            $table->string('name',100)->nullable();           
            $table->string('code',30)->nullable();
            $table->string('output_name')->nullable();  
            $table->string('path')->nullable();           
            $table->string('file_name',100)->nullable();           
            $table->boolean('is_default')->default(false);           
            $table->log();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('report');
    }
};
