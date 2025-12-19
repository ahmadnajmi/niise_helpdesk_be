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
            $table->smallInteger('response_time_penalty_type')->nullable();
            $table->smallInteger('resolution_time_penalty_type')->nullable();
            $table->smallInteger('response_time_location_penalty_type')->nullable();
            $table->smallInteger('temporary_resolution_time_penalty_type')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sla_template', function (Blueprint $table) {
            $table->dropColumn('response_time_penalty_type');
            $table->dropColumn('resolution_time_penalty_type');
            $table->dropColumn('response_time_location_penalty_type');
            $table->dropColumn('temporary_resolution_time_penalty_type');

        });
    }
};
