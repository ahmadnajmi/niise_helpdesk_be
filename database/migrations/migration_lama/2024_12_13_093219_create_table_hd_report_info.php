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
        Schema::create('HD_Report_Info', function (Blueprint $table) {

            $table->string('ri_report_code',50);  // -- 1
            $table->string('ri_report_type',5); // -- 2
            $table->string('ri_report_name',100); // -- 3
            $table->string('ri_source_file',200); // -- 4
            $table->string('ri_compile_file',100); // -- 5
            $table->text('ri_parameters')->nullable(); // -- 6
            $table->datetime('ri_create_date'); // -- 7
            $table->string('ri_create_id',20); // -- 8
            $table->datetime('ri_update_date')->nullable(); // -- 9
            $table->string('ri_update_id',20)->nullable(); // -- 10
            $table->string('ri_customer_id',50)->nullable(); // -- 11
            $table->string('ri_report_var',3)->nullable(); // -- 12
            $table->string('ri_problem_type',50)->nullable(); // -- 13
            // -----------------------
            $table->timestamps();
            $table->softDeletes();
        });

        DB::statement('ALTER TABLE HD_Report_Info ALTER COLUMN ri_report_code VARCHAR(50)');
        DB::statement('ALTER TABLE HD_Report_Info ALTER COLUMN ri_report_type VARCHAR(5)');
        DB::statement('ALTER TABLE HD_Report_Info ALTER COLUMN ri_report_name VARCHAR(100)');
        DB::statement('ALTER TABLE HD_Report_Info ALTER COLUMN ri_source_file VARCHAR(200)');
        DB::statement('ALTER TABLE HD_Report_Info ALTER COLUMN ri_compile_file VARCHAR(100)');
        DB::statement('ALTER TABLE HD_Report_Info ALTER COLUMN ri_create_id VARCHAR(20)');
        DB::statement('ALTER TABLE HD_Report_Info ALTER COLUMN ri_update_id VARCHAR(20)');
        DB::statement('ALTER TABLE HD_Report_Info ALTER COLUMN ri_customer_id VARCHAR(50)');
        DB::statement('ALTER TABLE HD_Report_Info ALTER COLUMN ri_report_var VARCHAR(3)');
        DB::statement('ALTER TABLE HD_Report_Info ALTER COLUMN ri_problem_type VARCHAR(50)');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('HD_Report_Info');
    }
};
