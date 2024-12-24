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
        Schema::create('AS_Vendor_Info', function (Blueprint $table) {

            $table->string('vi_vendor_ID',10); // -- 1
            $table->string('vi_vendor_Shortname',50); // -- 2
            $table->string('vi_vendor_Name',100); // -- 3
            $table->string('vi_ContactPerson',10)->nullable(); // -- 4
            $table->string('vi_address1',80)->nullable(); // -- 5
            $table->string('vi_address2',80)->nullable(); // -- 6
            $table->string('vi_address3',80)->nullable(); // -- 7
            $table->string('vi_postcode',10)->nullable(); // -- 8
            $table->string('vi_city',50)->nullable(); // -- 9
            $table->string('vi_state',3)->nullable(); // -- 10
            $table->string('vi_country',3)->nullable();// -- 11
            $table->string('vi_phone',20)->nullable(); // -- 12
            $table->string('vi_fax',20)->nullable(); // -- 13
            $table->string('vi_email',100)->nullable(); // -- 14
            $table->string('vi_VendorType',10); // -- 15
            $table->string('vi_VendorSts',2); // -- 16
            $table->datetime('vi_contract_start'); // -- 17
            $table->datetime('vi_contract_end'); // -- 18
            $table->string('vi_msgp_code',15)->nullable(); // -- 19
            $table->string('vi_create_id',20)->nullable(); // -- 20
            $table->datetime('vi_create_date')->nullable(); // -- 21
            $table->string('vi_update_id', 20)->nullable(); // -- 22
            $table->datetime('vi_update_date')->nullable(); // -- 23
            $table->string('vi_active_sts',1); // -- 24
            // ---------------------
            $table->timestamps();
            $table->softDeletes();
        });

        DB::statement('ALTER TABLE AS_Vendor_Info ALTER COLUMN vi_vendor_ID VARCHAR(10)');
        DB::statement('ALTER TABLE AS_Vendor_Info ALTER COLUMN vi_vendor_Shortname VARCHAR(50)');
        DB::statement('ALTER TABLE AS_Vendor_Info ALTER COLUMN vi_vendor_Name VARCHAR(100)');
        DB::statement('ALTER TABLE AS_Vendor_Info ALTER COLUMN vi_ContactPerson VARCHAR(10)');
        DB::statement('ALTER TABLE AS_Vendor_Info ALTER COLUMN vi_address1 VARCHAR(80)');
        DB::statement('ALTER TABLE AS_Vendor_Info ALTER COLUMN vi_address2 VARCHAR(80)');
        DB::statement('ALTER TABLE AS_Vendor_Info ALTER COLUMN vi_address3 VARCHAR(80)');
        DB::statement('ALTER TABLE AS_Vendor_Info ALTER COLUMN vi_postcode VARCHAR(10)');
        DB::statement('ALTER TABLE AS_Vendor_Info ALTER COLUMN vi_city VARCHAR(50)');
        DB::statement('ALTER TABLE AS_Vendor_Info ALTER COLUMN vi_state VARCHAR(3)');
        DB::statement('ALTER TABLE AS_Vendor_Info ALTER COLUMN vi_country VARCHAR(3)');
        DB::statement('ALTER TABLE AS_Vendor_Info ALTER COLUMN vi_phone VARCHAR(20)');
        DB::statement('ALTER TABLE AS_Vendor_Info ALTER COLUMN vi_fax VARCHAR(20)');
        DB::statement('ALTER TABLE AS_Vendor_Info ALTER COLUMN vi_email VARCHAR(100)');
        DB::statement('ALTER TABLE AS_Vendor_Info ALTER COLUMN vi_VendorType VARCHAR(10)');
        DB::statement('ALTER TABLE AS_Vendor_Info ALTER COLUMN vi_VendorSts VARCHAR(2)');
        DB::statement('ALTER TABLE AS_Vendor_Info ALTER COLUMN vi_msgp_code VARCHAR(15)');
        DB::statement('ALTER TABLE AS_Vendor_Info ALTER COLUMN vi_create_id VARCHAR(20)');
        DB::statement('ALTER TABLE AS_Vendor_Info ALTER COLUMN vi_update_id VARCHAR(20)');
        DB::statement('ALTER TABLE AS_Vendor_Info ALTER COLUMN vi_active_sts VARCHAR(1)');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('AS_Vendor_Info');
    }
};
