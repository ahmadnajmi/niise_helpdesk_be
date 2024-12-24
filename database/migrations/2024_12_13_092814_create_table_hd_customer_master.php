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
        Schema::create('HD_Customer_Master', function (Blueprint $table) {

            $table->string('cu_customer_ID',10);  // -- 1
            $table->string('cu_customer_Shortname',15); // -- 2
            $table->string('cu_customer_Name',100); // -- 3
            $table->string('cu_account_mgr',10)->nullable(); // -- 4
            $table->string('cu_account_mgr_phone',50)->nullable(); // -- 5
            $table->string('cu_account_mgr_email',50)->nullable(); // -- 6
            $table->string('cu_mgsp_code',15)->nullable(); // -- 7
            $table->string('cu_web_site',30)->nullable(); // -- 8
            $table->string('cu_create_id',20)->nullable(); // -- 9
            $table->datetime('cu_create_date'); // -- 10
            $table->string('cu_update_id',20)->nullable(); // -- 11
            $table->datetime('cu_update_date')->nullable(); // -- 12
            $table->string('cu_active_sts',5); // -- 13
            $table->string('cu_customer_type',50)->nullable(); // -- 14
            $table->datetime('cu_contract_start')->nullable(); // -- 15
            $table->datetime('cu_contract_end')->nullable(); // -- 16
            $table->integer('cu_template')->nullable(); // -- 17
            // -----------------------
            $table->timestamps();
            $table->softDeletes();
        });

        DB::statement('ALTER TABLE HD_Customer_Master ALTER COLUMN cu_customer_ID VARCHAR(10)');
        DB::statement('ALTER TABLE HD_Customer_Master ALTER COLUMN cu_customer_Shortname VARCHAR(15)');
        DB::statement('ALTER TABLE HD_Customer_Master ALTER COLUMN cu_customer_Name VARCHAR(100)');
        DB::statement('ALTER TABLE HD_Customer_Master ALTER COLUMN cu_account_mgr VARCHAR(10)');
        DB::statement('ALTER TABLE HD_Customer_Master ALTER COLUMN cu_account_mgr_phone VARCHAR(50)');
        DB::statement('ALTER TABLE HD_Customer_Master ALTER COLUMN cu_account_mgr_email VARCHAR(50)');
        DB::statement('ALTER TABLE HD_Customer_Master ALTER COLUMN cu_mgsp_code VARCHAR(15)');
        DB::statement('ALTER TABLE HD_Customer_Master ALTER COLUMN cu_web_site VARCHAR(30)');
        DB::statement('ALTER TABLE HD_Customer_Master ALTER COLUMN cu_create_id VARCHAR(20)');
        DB::statement('ALTER TABLE HD_Customer_Master ALTER COLUMN cu_update_id VARCHAR(20)');
        DB::statement('ALTER TABLE HD_Customer_Master ALTER COLUMN cu_active_sts VARCHAR(5)');
        DB::statement('ALTER TABLE HD_Customer_Master ALTER COLUMN cu_customer_type VARCHAR(50)');
    }

    /**S
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('HD_Customer_Master');
    }
};
