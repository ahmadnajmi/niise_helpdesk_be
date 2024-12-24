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
        Schema::create('AS_AssetMaster', function (Blueprint $table) {

            $table->string('am_SN_no', 50); // -- 1
            $table->string('am_tag_id', 50)->nullable(); // -- 2
            $table->string('am_service_tag', 50)->nullable(); // -- 3
            $table->string('am_rec_type', 1)->nullable(); // -- 4
            $table->string('am_asset_code', 50)->nullable(); // -- 5
            $table->string('am_customer_id', 10)->nullable(); // -- 6
            $table->string('am_branch_code', 10)->nullable(); // -- 7
            $table->string('am_owned_by', 10)->nullable(); // -- 8
            $table->string('am_PO_no', 50)->nullable(); // -- 9
            $table->string('am_DO_no', 50)->nullable(); // -- 10
            $table->string('am_INV_no', 10)->nullable(); // -- 11
            $table->string('am_supplier_ID', 10)->nullable(); // -- 12
            $table->dateTime('am_install_dt')->nullable(); // -- 13
            $table->string('am_asset_state', 2)->nullable(); // -- 14
            $table->string('am_asset_type', 1)->nullable(); // -- 15
            $table->string('am_vendor_id', 10)->nullable(); // -- 16
            $table->string('am_loan_to', 50)->nullable(); // -- 17
            $table->dateTime('am_loan_start')->nullable(); // -- 18
            $table->dateTime('am_loan_end')->nullable(); // -- 19
            $table->string('am_model', 50)->nullable(); // -- 20
            $table->string('am_specs', 254)->nullable(); // -- 21
            $table->string('am_description', 254)->nullable(); // -- 22
            $table->string('am_rec_sts', 1)->nullable(); // -- 23
            $table->string('am_isFixed_Asset', 1)->nullable(); // -- 24
            $table->string('am_tag_sts', 1)->nullable();  // -- 25
            $table->string('am_sparing_ind', 1)->nullable(); // -- 26
            $table->string('am_create_id', 50)->nullable(); // -- 27
            $table->dateTime('am_create_date')->nullable(); // -- 28
            $table->string('am_update_id', 20)->nullable(); // -- 29
            $table->dateTime('am_update_date')->nullable(); // -- 30
            $table->string('am_status_rec', 3)->nullable(); // -- 31
            // ------------------
            $table->timestamps();
            $table->softDeletes();
        });

        // Alter '...' column to VARCHAR
        DB::statement('ALTER TABLE AS_AssetMaster ALTER COLUMN am_SN_no VARCHAR(50)');
        DB::statement('ALTER TABLE AS_AssetMaster ALTER COLUMN am_tag_id VARCHAR(50)');
        DB::statement('ALTER TABLE AS_AssetMaster ALTER COLUMN am_service_tag VARCHAR(50)');
        DB::statement('ALTER TABLE AS_AssetMaster ALTER COLUMN am_rec_type VARCHAR(1)');
        DB::statement('ALTER TABLE AS_AssetMaster ALTER COLUMN am_asset_code VARCHAR(50)');
        DB::statement('ALTER TABLE AS_AssetMaster ALTER COLUMN am_customer_id VARCHAR(10)');
        DB::statement('ALTER TABLE AS_AssetMaster ALTER COLUMN am_branch_code VARCHAR(10)');
        DB::statement('ALTER TABLE AS_AssetMaster ALTER COLUMN am_owned_by VARCHAR(10)');
        DB::statement('ALTER TABLE AS_AssetMaster ALTER COLUMN am_PO_no VARCHAR(50)');
        DB::statement('ALTER TABLE AS_AssetMaster ALTER COLUMN am_DO_no VARCHAR(50)');
        DB::statement('ALTER TABLE AS_AssetMaster ALTER COLUMN am_INV_no VARCHAR(10)');
        DB::statement('ALTER TABLE AS_AssetMaster ALTER COLUMN am_supplier_ID VARCHAR(10)');
        DB::statement('ALTER TABLE AS_AssetMaster ALTER COLUMN am_asset_state VARCHAR(2)');
        DB::statement('ALTER TABLE AS_AssetMaster ALTER COLUMN am_asset_type VARCHAR(1)');
        DB::statement('ALTER TABLE AS_AssetMaster ALTER COLUMN am_vendor_id VARCHAR(10)');
        DB::statement('ALTER TABLE AS_AssetMaster ALTER COLUMN am_loan_to VARCHAR(50)');
        DB::statement('ALTER TABLE AS_AssetMaster ALTER COLUMN am_model VARCHAR(50)');
        DB::statement('ALTER TABLE AS_AssetMaster ALTER COLUMN am_specs VARCHAR(254)');
        DB::statement('ALTER TABLE AS_AssetMaster ALTER COLUMN am_description VARCHAR(254)');
        DB::statement('ALTER TABLE AS_AssetMaster ALTER COLUMN am_rec_sts VARCHAR(1)');
        DB::statement('ALTER TABLE AS_AssetMaster ALTER COLUMN am_isFixed_Asset VARCHAR(1)');
        DB::statement('ALTER TABLE AS_AssetMaster ALTER COLUMN am_tag_sts VARCHAR(1)');
        DB::statement('ALTER TABLE AS_AssetMaster ALTER COLUMN am_sparing_ind VARCHAR(1)');
        DB::statement('ALTER TABLE AS_AssetMaster ALTER COLUMN am_create_id VARCHAR(20)');
        DB::statement('ALTER TABLE AS_AssetMaster ALTER COLUMN am_update_id VARCHAR(20)');
        DB::statement('ALTER TABLE AS_AssetMaster ALTER COLUMN am_status_rec VARCHAR(3)');
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {

        Schema::dropIfExists('AS_AssetMaster');
    }
};
