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
        Schema::create('logcreated', function (Blueprint $table) {

            $table->string('cm_log_no',15);  // -- 1
            $table->string('cm_rectype',1)->nullable(); // -- 2
            $table->string('cm_category',50)->nullable(); // -- 3
            $table->string('cm_severity',4)->nullable(); // -- 4
            $table->string('cm_sla_code',35)->nullable(); // -- 5
            $table->string('cm_status',2)->nullable(); // -- 6
            $table->string('cm_mode',5)->nullable(); // -- 7
            $table->string('cm_fwd_to',20)->nullable(); // -- 8
            $table->string('cm_customer_rptno',50)->nullable(); // -- 9
            $table->string('cm_customer_id',10); // -- 10
            $table->string('cm_branch_code',10); // -- 11
            $table->string('cm_caller_id',50)->nullable(); // -- 12
            $table->string('cm_person_id',50)->nullable(); // -- 13
            $table->datetime('cm_call_start')->nullable(); // -- 14
            $table->datetime('cm_call_end')->nullable(); // -- 15
            $table->string('cm_asset_sn',30)->nullable(); // -- 16
            $table->string('cm_vendor_id',10)->nullable(); // -- 17
            $table->string('cm_vendor_rptno',50)->nullable(); // -- 18
            $table->datetime('cm_start_date')->nullable(); // -- 19
            $table->datetime('cm_due_datetm')->nullable(); // -- 20
            $table->datetime('cm_resolve_datetm')->nullable(); // -- 21
            $table->datetime('cm_close_datetm')->nullable(); // -- 22
            $table->string('cm_description',1000)->nullable(); // -- 23
            $table->string('cm_create_id',20); // -- 24
            $table->datetime('cm_create_date'); // -- 25
            $table->string('cm_update_id',20)->nullable(); // -- 26
            $table->datetime('cm_update_date')->nullable(); // -- 27
            $table->integer('cm_downtime')->nullable(); // -- 28
            $table->integer('cm_disclaimer')->nullable(); // -- 29
            $table->string('cm_group_id',20)->nullable(); // -- 30
            $table->string('cm_problem_indicator',5)->nullable(); // -- 31
            $table->string('cm_jpn_problem_type',50)->nullable(); // -- 32
            // ----------------------
            $table->timestamps();
            $table->softDeletes();
        });

        DB::statement('ALTER TABLE logcreated ALTER COLUMN cm_log_no VARCHAR(15)');
        DB::statement('ALTER TABLE logcreated ALTER COLUMN cm_rectype VARCHAR(1)');
        DB::statement('ALTER TABLE logcreated ALTER COLUMN cm_category VARCHAR(50)');
        DB::statement('ALTER TABLE logcreated ALTER COLUMN cm_severity VARCHAR(4)');
        DB::statement('ALTER TABLE logcreated ALTER COLUMN cm_sla_code VARCHAR(35)');
        DB::statement('ALTER TABLE logcreated ALTER COLUMN cm_status VARCHAR(2)');
        DB::statement('ALTER TABLE logcreated ALTER COLUMN cm_mode VARCHAR(5)');
        DB::statement('ALTER TABLE logcreated ALTER COLUMN cm_fwd_to VARCHAR(20)');
        DB::statement('ALTER TABLE logcreated ALTER COLUMN cm_customer_rptno VARCHAR(50)');
        DB::statement('ALTER TABLE logcreated ALTER COLUMN cm_customer_id VARCHAR(10)');
        DB::statement('ALTER TABLE logcreated ALTER COLUMN cm_branch_code VARCHAR(10)');
        DB::statement('ALTER TABLE logcreated ALTER COLUMN cm_caller_id VARCHAR(50)');
        DB::statement('ALTER TABLE logcreated ALTER COLUMN cm_person_id VARCHAR(50)');
        DB::statement('ALTER TABLE logcreated ALTER COLUMN cm_call_start DATETIME2(7)');
        DB::statement('ALTER TABLE logcreated ALTER COLUMN cm_call_end DATETIME2(7)');
        DB::statement('ALTER TABLE logcreated ALTER COLUMN cm_asset_sn VARCHAR(30)');
        DB::statement('ALTER TABLE logcreated ALTER COLUMN cm_vendor_id VARCHAR(10)');
        DB::statement('ALTER TABLE logcreated ALTER COLUMN cm_vendor_rptno VARCHAR(50)');
        DB::statement('ALTER TABLE logcreated ALTER COLUMN cm_start_date DATETIME2(7)');
        DB::statement('ALTER TABLE logcreated ALTER COLUMN cm_due_datetm DATETIME2(7)');
        DB::statement('ALTER TABLE logcreated ALTER COLUMN cm_resolve_datetm DATETIME2(7)');
        DB::statement('ALTER TABLE logcreated ALTER COLUMN cm_close_datetm DATETIME2(7)');
        DB::statement('ALTER TABLE logcreated ALTER COLUMN cm_description VARCHAR(1000)');
        DB::statement('ALTER TABLE logcreated ALTER COLUMN cm_create_id VARCHAR(20)');
        DB::statement('ALTER TABLE logcreated ALTER COLUMN cm_create_date DATETIME2(7)');
        DB::statement('ALTER TABLE logcreated ALTER COLUMN cm_update_id VARCHAR(20)');
        DB::statement('ALTER TABLE logcreated ALTER COLUMN cm_update_date DATETIME2(7)');
        DB::statement('ALTER TABLE logcreated ALTER COLUMN cm_group_id VARCHAR(20)');
        DB::statement('ALTER TABLE logcreated ALTER COLUMN cm_problem_indicator VARCHAR(5)');
        DB::statement('ALTER TABLE logcreated ALTER COLUMN cm_jpn_problem_type VARCHAR(50)');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('logcreated');
    }
};
