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
        Schema::create('HD_Person_Info', function (Blueprint $table) {

            $table->string('pi_person_id',10);  // -- 1
            $table->string('pi_person_type',1); // -- 2
            $table->string('pi_name',60); // -- 3
            $table->string('pi_customer_id',10)->nullable(); // -- 4
            $table->string('pi_vendor_id',10)->nullable(); // -- 5
            $table->string('pi_supplier_id',10)->nullable(); // -- 6
            $table->string('pi_branchcode',10)->nullable(); // -- 7
            $table->string('pi_handphone_no',25)->nullable(); // -- 8
            $table->string('pi_did_no',25)->nullable(); // -- 9
            $table->string('pi_email',50)->nullable(); // -- 10
            $table->string('pi_fax_no',20)->nullable(); // -- 11
            $table->string('pi_level',1)->nullable(); // -- 12
            $table->string('pi_designation',30)->nullable(); // -- 13
            $table->string('pi_department',50)->nullable(); // -- 14
            $table->string('pi_create_id',20)->nullable(); // -- 15
            $table->datetime('pi_create_date')->nullable(); // -- 16
            $table->string('pi_update_id',20)->nullable(); // -- 17
            $table->datetime('pi_update_date')->nullable(); // -- 18
            $table->string('pi_active_sts',5); // -- 19
            $table->string('pi_ext_no',25)->nullable(); // -- 20
            $table->string('pi_remarks',80)->nullable(); // -- 21
            $table->unsignedInteger('pi_id')->primary(); // -- 22
            // -----------------------
            $table->timestamps();
            $table->softDeletes();
        });

        DB::statement('ALTER TABLE HD_Person_Info ALTER COLUMN pi_person_id VARCHAR(10)');
        DB::statement('ALTER TABLE HD_Person_Info ALTER COLUMN pi_person_type VARCHAR(1)');
        DB::statement('ALTER TABLE HD_Person_Info ALTER COLUMN pi_name VARCHAR(60)');
        DB::statement('ALTER TABLE HD_Person_Info ALTER COLUMN pi_customer_id VARCHAR(10)');
        DB::statement('ALTER TABLE HD_Person_Info ALTER COLUMN pi_vendor_id VARCHAR(10)');
        DB::statement('ALTER TABLE HD_Person_Info ALTER COLUMN pi_supplier_id VARCHAR(10)');
        DB::statement('ALTER TABLE HD_Person_Info ALTER COLUMN pi_branchcode VARCHAR(10)');
        DB::statement('ALTER TABLE HD_Person_Info ALTER COLUMN pi_handphone_no VARCHAR(25)');
        DB::statement('ALTER TABLE HD_Person_Info ALTER COLUMN pi_did_no VARCHAR(25)');
        DB::statement('ALTER TABLE HD_Person_Info ALTER COLUMN pi_email VARCHAR(50)');
        DB::statement('ALTER TABLE HD_Person_Info ALTER COLUMN pi_fax_no VARCHAR(20)');
        DB::statement('ALTER TABLE HD_Person_Info ALTER COLUMN pi_level VARCHAR(1)');
        DB::statement('ALTER TABLE HD_Person_Info ALTER COLUMN pi_designation VARCHAR(30)');
        DB::statement('ALTER TABLE HD_Person_Info ALTER COLUMN pi_department VARCHAR(50)');
        DB::statement('ALTER TABLE HD_Person_Info ALTER COLUMN pi_create_id VARCHAR(20)');
        DB::statement('ALTER TABLE HD_Person_Info ALTER COLUMN pi_update_id VARCHAR(20)');
        DB::statement('ALTER TABLE HD_Person_Info ALTER COLUMN pi_active_sts VARCHAR(5)');
        DB::statement('ALTER TABLE HD_Person_Info ALTER COLUMN pi_ext_no VARCHAR(25)');
        DB::statement('ALTER TABLE HD_Person_Info ALTER COLUMN pi_remarks VARCHAR(80)');

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('HD_Person_Info');
    }
};
