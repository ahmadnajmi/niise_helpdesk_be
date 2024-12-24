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
        Schema::create('HD_Customer_Branch', function (Blueprint $table) {

            $table->string('cb_customer_ID',50); // -- 1
            $table->string('cb_branchcode',50); // -- 2
            $table->string('cb_branch_Name',100); // -- 3
            $table->string('cb_address1',255); // -- 4
            $table->string('cb_address2',80)->nullable(); // -- 5
            $table->string('cb_address3',80)->nullable(); // -- 6
            $table->string('cb_postcode',10)->nullable(); // -- 7
            $table->string('cb_city',50)->nullable(); // -- 8
            $table->string('cb_state',50)->nullable(); // -- 9
            $table->string('cb_country',50)->nullable(); // -- 10
            $table->string('cb_general_line',20)->nullable(); // -- 11
            $table->string('cb_Hol_Code',50)->nullable(); // -- 12
            $table->string('cb_bizhour_code',50)->nullable(); // -- 13
            $table->string('cb_nearest_code',50)->nullable(); // -- 14
            $table->integer('cb_node_distance')->nullable(); // -- 15
            $table->string('cb_connected_node',50)->nullable(); // -- 16
            $table->string('cb_is_critical',50)->nullable(); // -- 17
            $table->string('cb_create_id',20); // -- 18
            $table->datetime('cb_create_date'); // -- 19
            $table->string('cb_update_id',20)->nullable(); // -- 20
            $table->datetime('cb_update_date')->nullable(); // -- 21
            $table->string('cb_active_sts',1); // -- 22
            $table->string('cb_remarks',500)->nullable(); // -- 23
            $table->string('cb_indication',3)->nullable(); // -- 24
            $table->unsignedBigInteger('ID')->primary(); // -- 25
            // -----------------------
            $table->timestamps();
            $table->softDeletes();
        });

        DB::statement('ALTER TABLE HD_Customer_Branch ALTER COLUMN cb_customer_ID VARCHAR(50)');
        DB::statement('ALTER TABLE HD_Customer_Branch ALTER COLUMN cb_branchcode VARCHAR(50)');
        DB::statement('ALTER TABLE HD_Customer_Branch ALTER COLUMN cb_branch_Name VARCHAR(100)');
        DB::statement('ALTER TABLE HD_Customer_Branch ALTER COLUMN cb_address1 VARCHAR(255)');
        DB::statement('ALTER TABLE HD_Customer_Branch ALTER COLUMN cb_address2 VARCHAR(80)');
        DB::statement('ALTER TABLE HD_Customer_Branch ALTER COLUMN cb_address3 VARCHAR(80)');
        DB::statement('ALTER TABLE HD_Customer_Branch ALTER COLUMN cb_postcode VARCHAR(10)');
        DB::statement('ALTER TABLE HD_Customer_Branch ALTER COLUMN cb_city VARCHAR(50)');
        DB::statement('ALTER TABLE HD_Customer_Branch ALTER COLUMN cb_state VARCHAR(50)');
        DB::statement('ALTER TABLE HD_Customer_Branch ALTER COLUMN cb_country VARCHAR(50)');
        DB::statement('ALTER TABLE HD_Customer_Branch ALTER COLUMN cb_general_line VARCHAR(20)');
        DB::statement('ALTER TABLE HD_Customer_Branch ALTER COLUMN cb_Hol_Code VARCHAR(50)');
        DB::statement('ALTER TABLE HD_Customer_Branch ALTER COLUMN cb_bizhour_code VARCHAR(50)');
        DB::statement('ALTER TABLE HD_Customer_Branch ALTER COLUMN cb_nearest_code VARCHAR(50)');
        DB::statement('ALTER TABLE HD_Customer_Branch ALTER COLUMN cb_connected_node VARCHAR(50)');
        DB::statement('ALTER TABLE HD_Customer_Branch ALTER COLUMN cb_is_critical VARCHAR(50)');
        DB::statement('ALTER TABLE HD_Customer_Branch ALTER COLUMN cb_create_id VARCHAR(20)');
        DB::statement('ALTER TABLE HD_Customer_Branch ALTER COLUMN cb_update_id VARCHAR(20)');
        DB::statement('ALTER TABLE HD_Customer_Branch ALTER COLUMN cb_active_sts VARCHAR(1)');
        DB::statement('ALTER TABLE HD_Customer_Branch ALTER COLUMN cb_remarks VARCHAR(500)');
        DB::statement('ALTER TABLE HD_Customer_Branch ALTER COLUMN cb_indication VARCHAR(3)');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('HD_Customer_Branch');
    }
};
