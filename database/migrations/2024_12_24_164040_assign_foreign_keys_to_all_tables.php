<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {

    //     DB::statement("ALTER TABLE com_status ALTER COLUMN ID VARCHAR(3) NOT NULL");
    //     DB::statement("ALTER TABLE refAction ALTER COLUMN ac_code VARCHAR(10) NOT NULL");
    //     DB::statement("ALTER TABLE refAction ALTER COLUMN ac_status_rec VARCHAR(3) NOT NULL");

    //     Schema::table("com_status", function (Blueprint $table) {
    //         $table->primary("ID");
    //     });

    //     // Ensure data consistency before adding foreign key
    //     DB::statement("UPDATE refAction SET ac_status_rec = 'default_value' WHERE ac_status_rec IS NULL OR ac_status_rec NOT IN (SELECT ID FROM com_status)");

    //     Schema::table("refAction", function (Blueprint $table) {
    //         $table->primary("ac_code");
    //         $table->foreign('ac_status_rec')->references('ID')->on('com_status'); // Add foreign key
    //     });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
    //     Schema::table("refAction", function (Blueprint $table) {
    //         $table->dropForeign(['ac_status_rec']); // Drop foreign key
    //     });
    }
};
