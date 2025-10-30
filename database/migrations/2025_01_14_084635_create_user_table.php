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
        Schema::create('user', function (Blueprint $table) {
            $table->id();
            $table->string('ic_no',12);
            $table->string('email',100)->nullable(); 
            $table->string('name',100)->nullable(); 
            $table->string('nickname',100)->nullable(); 
            $table->string('password')->nullable(); 
            $table->string('position',100)->nullable(); 
            $table->unsignedBigInteger('branch_id')->nullable(); 
            $table->unsignedBigInteger('company_id')->nullable();
            $table->string('phone_no',20)->nullable(); 
            $table->string('address',255)->nullable(); 
            $table->integer('postcode')->nullable(); 
            $table->string('city',100)->nullable(); 
            $table->integer('state_id')->nullable(); 
            $table->string('fax_no',100)->nullable(); 
            $table->string('category_office',100)->nullable();
            $table->smallInteger('user_type')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user');
    }
};