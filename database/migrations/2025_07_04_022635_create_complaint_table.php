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
        Schema::create('complaint', function (Blueprint $table) {
            $table->id();
            $table->string('name',100)->nullable(); 
            $table->string('email',100)->nullable(); 
            $table->string('phone_no',20)->nullable(); 
            $table->string('office_phone_no',20)->nullable();
            $table->string('extension_no',20)->nullable();
        
            $table->log();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('complaint');
    }
};
