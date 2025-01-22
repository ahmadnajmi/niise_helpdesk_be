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
        Schema::create('HD_Report_Email', function (Blueprint $table) {

            $table->string('re_report_code',50);  // -- 1
            $table->string('re_report_type',3)->nullable(); // -- 2
            $table->text('re_content')->nullable(); // -- 3
            $table->string('re_name',200)->nullable(); // -- 4
            $table->string('re_email',50)->nullable(); // -- 5
            $table->string('re_attachment',300)->nullable(); // -- 6
            // -------------------------
            $table->timestamps();
            $table->softDeletes();
        });

        DB::statement('ALTER TABLE HD_Report_Email ALTER COLUMN re_report_code VARCHAR(50)');
        DB::statement('ALTER TABLE HD_Report_Email ALTER COLUMN re_report_type VARCHAR(3)');
        DB::statement('ALTER TABLE HD_Report_Email ALTER COLUMN re_name VARCHAR(200)');
        DB::statement('ALTER TABLE HD_Report_Email ALTER COLUMN re_email VARCHAR(50)');
        DB::statement('ALTER TABLE HD_Report_Email ALTER COLUMN re_attachment VARCHAR(300)');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('HD_Report_Email');
    }
};
