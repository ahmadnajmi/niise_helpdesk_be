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
        Schema::create('action_codes', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('nickname');
            $table->string('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->boolean('send_email')->default(true);
            $table->smallInteger('email_recipient_id')->nullable();
            $table->log();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('action_codes');
    }
};
