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
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->string('name',100)->nullable(); 
            $table->string('nickname',100)->nullable(); 
            $table->string('email',100)->nullable(); 
            $table->string('phone_no',20)->nullable(); 
            $table->string('address',255)->nullable(); 
            $table->integer('postcode')->nullable(); 
            $table->string('city',100)->nullable(); 
            $table->integer('state_id')->nullable(); 
            $table->string('fax_no',100)->nullable(); 
            $table->string('description',1000)->nullable();
            $table->boolean('is_active')->default(true);
            $table->log();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('companies');
    }
};
