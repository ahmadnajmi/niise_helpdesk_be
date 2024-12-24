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
        Schema::create('HD_RelCust_Category', function (Blueprint $table) {

            $table->string('cc_customer_id',10);  // -- 1
            $table->string('cc_category_code',50); // -- 2
            $table->string('cc_branch_code',10); // -- 3
            $table->string('cc_sla_code',50)->nullable(); // -- 4
            $table->char('cc_notify_sts',1)->nullable(); // -- 5
            $table->char('cc_subscribe_sts',1)->nullable(); // -- 6
            $table->string('cc_create_id',20); // -- 7
            $table->datetime('cc_create_date'); // -- 8
            $table->string('cc_update_id',20)->nullable(); // -- 9
            $table->datetime('cc_update_date')->nullable(); // -- 10
            $table->char('cc_status_rec',3)->nullable(); // -- 11
            // ----------------------------------
            $table->timestamps();
            $table->softDeletes();
        });

        DB::statement('ALTER TABLE HD_RelCust_Category ALTER COLUMN cc_customer_id VARCHAR(10)');
        DB::statement('ALTER TABLE HD_RelCust_Category ALTER COLUMN cc_category_code VARCHAR(50)');
        DB::statement('ALTER TABLE HD_RelCust_Category ALTER COLUMN cc_branch_code VARCHAR(10)');
        DB::statement('ALTER TABLE HD_RelCust_Category ALTER COLUMN cc_sla_code VARCHAR(50)');
        DB::statement('ALTER TABLE HD_RelCust_Category ALTER COLUMN cc_notify_sts CHAR(1)');
        DB::statement('ALTER TABLE HD_RelCust_Category ALTER COLUMN cc_subscribe_sts CHAR(1)');
        DB::statement('ALTER TABLE HD_RelCust_Category ALTER COLUMN cc_create_id VARCHAR(20)');
        DB::statement('ALTER TABLE HD_RelCust_Category ALTER COLUMN cc_update_id VARCHAR(20)');
        DB::statement('ALTER TABLE HD_RelCust_Category ALTER COLUMN cc_status_rec CHAR(3)');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('HD_RelCust_Category');
    }
};
