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
        Schema::table('sla_template', function (Blueprint $table) {
            $table->string('verify_resolution_time')->nullable();
            $table->smallInteger('verify_resolution_time_type')->nullable();
            $table->string('verify_resolution_time_penalty',20)->nullable();
            $table->smallInteger('verify_resolution_time_penalty_type')->nullable();

            $table->dropColumn('dispatch_time');
            $table->dropColumn('dispatch_time_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sla_template', function (Blueprint $table) {
            $table->dropColumn('verify_resolution_time');
            $table->dropColumn('verify_resolution_time_type');
            $table->dropColumn('verify_resolution_time_penalty');
            $table->dropColumn('verify_resolution_time_penalty_type');

            $table->string('dispatch_time')->nullable();
            $table->smallInteger('dispatch_time_type')->nullable();
        });
    }

};
