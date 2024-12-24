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
        Schema::create('person', function (Blueprint $table) {

            $table->char('pi_person_id',10);  // -- 1
            $table->char('pi_person_type',1); // -- 2
            $table->string('pi_name',60); // -- 3
            $table->char('pi_customer_id',10)->nullable(); // -- 4
            $table->char('pi_vendor_id',10)->nullable(); // -- 5
            $table->char('pi_branchcode',10)->nullable(); // -- 6
            $table->char('pi_handphone_no',25)->nullable(); // -- 7
            $table->char('pi_did_no',25)->nullable(); // -- 8
            $table->string('pi_email',50)->nullable(); // -- 9
            $table->string('pi_fax_no',20)->nullable(); // -- 10
            $table->char('pi_level',1)->nullable(); // -- 11
            $table->string('pi_designation',30)->nullable(); // -- 12
            $table->string('pi_department',50)->nullable(); // -- 13
            $table->string('pi_create_id',20)->nullable(); // -- 14
            $table->datetime('pi_create_date')->nullable(); // -- 15
            $table->string('pi_update_id',20)->nullable(); // -- 16
            $table->datetime('pi_update_date')->nullable(); // -- 17
            $table->char('pi_active_sts',5); // -- 18
            $table->string('pi_ext_no',25)->nullable(); // -- 19
            // -----------------------
            $table->timestamps();
            $table->softDeletes();
        });

        DB::statement('ALTER TABLE person ALTER COLUMN pi_person_id CHAR(10)');
        DB::statement('ALTER TABLE person ALTER COLUMN pi_person_type CHAR(1)');
        DB::statement('ALTER TABLE person ALTER COLUMN pi_name VARCHAR(60)');
        DB::statement('ALTER TABLE person ALTER COLUMN pi_customer_id CHAR(10)');
        DB::statement('ALTER TABLE person ALTER COLUMN pi_vendor_id CHAR(10)');
        DB::statement('ALTER TABLE person ALTER COLUMN pi_branchcode CHAR(10)');
        DB::statement('ALTER TABLE person ALTER COLUMN pi_handphone_no CHAR(25)');
        DB::statement('ALTER TABLE person ALTER COLUMN pi_did_no CHAR(25)');
        DB::statement('ALTER TABLE person ALTER COLUMN pi_email VARCHAR(50)');
        DB::statement('ALTER TABLE person ALTER COLUMN pi_fax_no VARCHAR(20)');
        DB::statement('ALTER TABLE person ALTER COLUMN pi_level CHAR(1)');
        DB::statement('ALTER TABLE person ALTER COLUMN pi_designation VARCHAR(30)');
        DB::statement('ALTER TABLE person ALTER COLUMN pi_department VARCHAR(50)');
        DB::statement('ALTER TABLE person ALTER COLUMN pi_create_id VARCHAR(20)');
        DB::statement('ALTER TABLE person ALTER COLUMN pi_update_id VARCHAR(20)');
        DB::statement('ALTER TABLE person ALTER COLUMN pi_active_sts CHAR(5)');
        DB::statement('ALTER TABLE person ALTER COLUMN pi_ext_no VARCHAR(25)');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('person');
    }
};
