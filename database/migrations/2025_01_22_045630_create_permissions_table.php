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
        Schema::create('permissions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('role_id')->index();
            $table->unsignedBigInteger('sub_module_id');
            $table->boolean('allowed_list')->default(true);
            $table->boolean('allowed_create')->default(true);
            $table->boolean('allowed_view')->default(true);
            $table->boolean('allowed_update')->default(true);
            $table->boolean('allowed_delete')->default(true);
            $table->log();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('permissions');
    }
};
