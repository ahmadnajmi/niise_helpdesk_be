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
        Schema::create('user_groups', function (Blueprint $table) {
            $table->uuid('id')->unique()->primary();
            $table->uuid('groups_id')->nullable()->index(); 
            $table->smallInteger('user_type')->nullable();
            $table->string('ic_no',12)->nullable()->index(); 
            $table->string('name',100)->nullable();
            $table->string('email',100)->nullable();
            $table->unsignedBigInteger('company_id')->nullable();
            $table->log();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_groups');
    }
};
