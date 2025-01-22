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
        Schema::create('branch', function (Blueprint $table) {

            $table->char('cb_customer_ID',10); // -- 1
            $table->char('cb_branchcode',10); // -- 2
            $table->string('cb_branch_Name',100); // -- 3
            $table->string('cb_address1',80); // -- 4
            $table->string('cb_address2',80)->nullable(); // -- 5
            $table->string('cb_address3',80)->nullable(); // -- 6
            $table->string('cb_postcode',10)->nullable(); // -- 7
            $table->string('cb_city',50)->nullable(); // -- 8
            $table->char('cb_state',3)->nullable(); // // -- 9
            $table->char('cb_country',3)->nullable();  // -- 10
            $table->string('cb_general_line', 20)->nullable(); // -- 11
            $table->char('cb_Hol_Code',4)->nullable(); // -- 12
            $table->char('cb_bizhour_code',4)->nullable();// -- 13
            $table->char('cb_nearest_node',10)->nullable(); // -- 14
            $table->integer('cb_node_distance')->nullable(); // -- 15
            $table->char('cb_connected_node',10)->nullable(); // -- 16
            $table->char('cb_is_critical',1)->nullable(); // -- 17
            $table->string('cb_create_id',20); // -- 18
            $table->datetime('cb_create_date'); // -- 19
            $table->string('cb_update_id',20)->nullable(); // -- 20
            $table->datetime('cb_update_date')->nullable(); // -- 21
            $table->char('cb_active_sts',10); // -- 22
            $table->char('cb_remarks',3)->nullable(); // -- 23
            $table->string('cb_indication',500)->nullable(); // -- 24
            // ------------------
            $table->timestamps();
            $table->softDeletes();
        });

        DB::statement('ALTER TABLE branch ALTER COLUMN cb_customer_ID CHAR(10)');
        DB::statement('ALTER TABLE branch ALTER COLUMN cb_branchcode CHAR(10)');
        DB::statement('ALTER TABLE branch ALTER COLUMN cb_branch_Name VARCHAR(100)');
        DB::statement('ALTER TABLE branch ALTER COLUMN cb_address1 VARCHAR(80)');
        DB::statement('ALTER TABLE branch ALTER COLUMN cb_address2 VARCHAR(80)');
        DB::statement('ALTER TABLE branch ALTER COLUMN cb_address3 VARCHAR(80)');
        DB::statement('ALTER TABLE branch ALTER COLUMN cb_postcode VARCHAR(10)');
        DB::statement('ALTER TABLE branch ALTER COLUMN cb_city VARCHAR(50)');
        DB::statement('ALTER TABLE branch ALTER COLUMN cb_state CHAR(3)');
        DB::statement('ALTER TABLE branch ALTER COLUMN cb_country CHAR(3)');
        DB::statement('ALTER TABLE branch ALTER COLUMN cb_general_line VARCHAR(20)');
        DB::statement('ALTER TABLE branch ALTER COLUMN cb_Hol_Code CHAR(4)');
        DB::statement('ALTER TABLE branch ALTER COLUMN cb_bizhour_code CHAR(4)');
        DB::statement('ALTER TABLE branch ALTER COLUMN cb_nearest_node CHAR(10)');
        DB::statement('ALTER TABLE branch ALTER COLUMN cb_connected_node CHAR(10)');
        DB::statement('ALTER TABLE branch ALTER COLUMN cb_is_critical CHAR(1)');
        DB::statement('ALTER TABLE branch ALTER COLUMN cb_create_id VARCHAR(20)');
        DB::statement('ALTER TABLE branch ALTER COLUMN cb_update_id VARCHAR(20)');
        DB::statement('ALTER TABLE branch ALTER COLUMN cb_active_sts CHAR(10)');
        DB::statement('ALTER TABLE branch ALTER COLUMN cb_remarks CHAR(3)');
        DB::statement('ALTER TABLE branch ALTER COLUMN cb_indication VARCHAR(500)');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('branch');
    }
};
