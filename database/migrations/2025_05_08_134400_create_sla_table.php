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
        Schema::create('sla', function (Blueprint $table) {
            $table->id();
            $table->string('code')->nullable();
            $table->unsignedBigInteger('category_id')->nullable(); 
            $table->smallInteger('state_id')->nullable();
            $table->unsignedBigInteger('branch_id')->nullable(); 
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->unsignedBigInteger('sla_template_id')->nullable(); 
            $table->unsignedBigInteger('group_id')->nullable(); 
            $table->boolean('is_active')->default(true);
            $table->log();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sla');
    }
};
