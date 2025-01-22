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
        Schema::create('PMM', function (Blueprint $table) {

            // what is this? all of this?
            $table->string('ID',255)->nullable();  // -- 1
            $table->string('cb_customer_ID',255)->nullable(); // -- 2
            $table->string('cb_branchcode',255)->nullable(); // -- 3
            $table->string('cb_branch_Name',255)->nullable(); // -- 4
            $table->string('cb_address1',255)->nullable(); // -- 5
            $table->string('cb_address2',255)->nullable(); // -- 6
            $table->string('cb_address3',255)->nullable(); // -- 7
            $table->string('cb_postcode',255)->nullable(); // -- 8
            $table->string('cb_city',255)->nullable(); // -- 9
            $table->string('cb_state',255)->nullable(); // -- 10
            $table->string('cb_country',255)->nullable(); // -- 11
            $table->string('cb_general_line',255)->nullable(); // -- 12
            $table->string('cb_Hol_Code',255)->nullable(); // -- 13
            $table->string('cb_bizhour_code',255)->nullable(); // -- 14
            $table->string('cb_nearest_node',255)->nullable(); // -- 15
            $table->string('cb_node_distance',255)->nullable(); // -- 16
            $table->string('cb_connected_node',255)->nullable(); // -- 17
            $table->string('cb_is_critical',255)->nullable(); // -- 18
            $table->string('cb_create_id',255)->nullable(); // -- 19
            $table->datetime('cb_create_date')->nullable(); // -- 20
            $table->string('cb_update_id',255)->nullable(); // -- 21
            $table->datetime('cb_update_date')->nullable(); // -- 22
            $table->string('cb_active_sts',255)->nullable(); // -- 23
            $table->string('cb_remarks',255)->nullable(); // -- 24
            $table->string('cb_indication',length: 255)->nullable(); // -- 25
            // -----------------------
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('PMM');
    }
};
