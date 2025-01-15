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
        Schema::connection('oracle_identity_management')->create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name',100)->nullable(); 
            $table->string('position',100)->nullable(); 
            $table->string('location')->nullable(); 
            $table->string('email',100)->nullable(); 
            $table->string('phone_no',20)->nullable(); 
            $table->string('category_office',100)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};