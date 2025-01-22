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
        Schema::create('HD_Case_Resolution', function (Blueprint $table) {

            $table->datetime('cr_reso_datetm');  // -- 1
            $table->string('cr_log_no',15); // -- 2
            $table->string('cr_action',10)->nullable(); // -- 3
            $table->string('cr_fwd_to',80)->nullable(); // -- 4
            $table->string('cr_resolution',1000)->nullable(); // -- 5 // Initial value: 5000
            $table->string('cr_resolve_id',20)->nullable(); // -- 6
            $table->string('cr_status_ke',1)->nullable(); // -- 7   // what is this?
            $table->string('cr_vendor_id',10)->nullable(); // -- 8
            $table->string('cr_vendor_rptno',50)->nullable(); // -- 9
            $table->string('cr_create_id',20); // -- 10
            $table->datetime('cr_create_date'); // -- 11
            $table->string('cr_update_id',20)->nullable(); // -- 12
            $table->datetime('cr_update_date')->nullable(); // -- 13
            $table->datetime('cr_end_date')->nullable(); // -- 14
            $table->string('cr_group_id',50)->nullable(); // -- 15
            // -------------------------
            $table->timestamps();
            $table->softDeletes();
        });

        DB::statement('ALTER TABLE HD_Case_Resolution ALTER COLUMN cr_log_no VARCHAR(15)');
        DB::statement('ALTER TABLE HD_Case_Resolution ALTER COLUMN cr_action VARCHAR(10)');
        DB::statement('ALTER TABLE HD_Case_Resolution ALTER COLUMN cr_fwd_to VARCHAR(80)');
        DB::statement('ALTER TABLE HD_Case_Resolution ALTER COLUMN cr_resolution VARCHAR(5000)');
        DB::statement('ALTER TABLE HD_Case_Resolution ALTER COLUMN cr_resolve_id VARCHAR(20)');
        DB::statement('ALTER TABLE HD_Case_Resolution ALTER COLUMN cr_status_ke VARCHAR(1)');
        DB::statement('ALTER TABLE HD_Case_Resolution ALTER COLUMN cr_vendor_id VARCHAR(10)');
        DB::statement('ALTER TABLE HD_Case_Resolution ALTER COLUMN cr_vendor_rptno VARCHAR(50)');
        DB::statement('ALTER TABLE HD_Case_Resolution ALTER COLUMN cr_create_id VARCHAR(20)');
        DB::statement('ALTER TABLE HD_Case_Resolution ALTER COLUMN cr_update_id VARCHAR(20)');
        DB::statement('ALTER TABLE HD_Case_Resolution ALTER COLUMN cr_group_id VARCHAR(50)');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('HD_Case_Resolution');
    }
};
