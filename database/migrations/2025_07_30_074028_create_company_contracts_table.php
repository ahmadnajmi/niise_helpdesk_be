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
        Schema::create('company_contracts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id')->nullable(); 
            $table->string('contract_no',100)->nullable();
            $table->string('name',100)->nullable(); 
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->boolean('is_active')->default(true);
            $table->smallInteger('status')->default(1);
            $table->log();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('company_contracts');
    }
};
