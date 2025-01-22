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
        Schema::create('AS_Supplier_Info', function (Blueprint $table) {

            $table->string('si_supplier_ID',10); // -- 1
            $table->string('si_supplier_shortname',50); // -- 2
            $table->string('si_supplier_name',100); // -- 3
            $table->string('si_contact_person',10)->nullable(); // -- 4
            $table->string('si_address1',80)->nullable(); // -- 5
            $table->string('si_address2',80)->nullable(); // -- 6
            $table->string('si_address3',80)->nullable(); // -- 7
            $table->string('si_postcode',10)->nullable(); // -- 8
            $table->string('si_city',50)->nullable(); // -- 9
            $table->string('si_state',3)->nullable(); // -- 10
            $table->string('si_country',3)->nullable(); // -- 11
            $table->string('si_phone',20)->nullable(); // -- 12
            $table->string('si_fax',20)->nullable(); // -- 13
            $table->string('si_email',100)->nullable(); // -- 14
            $table->datetime('si_contract_start'); // -- 15
            $table->datetime('si_contract_end'); // -- 16
            $table->string('si_mgsp_code',15)->nullable(); // -- 17
            $table->datetime('si_create_date'); // -- 18
            $table->string('si_create_id',20); // -- 19
            $table->datetime('si_update_date')->nullable(); // -- 20
            $table->string('si_update_id',20)->nullable(); // -- 21
            $table->string('si_active_sts',1)->nullable(); // -- 22
            // --------------------
            $table->timestamps();
            $table->softDeletes();
        });

        DB::statement('ALTER TABLE AS_Supplier_Info ALTER COLUMN si_supplier_ID VARCHAR(10)');
        DB::statement('ALTER TABLE AS_Supplier_Info ALTER COLUMN si_supplier_shortname VARCHAR(50)');
        DB::statement('ALTER TABLE AS_Supplier_Info ALTER COLUMN si_supplier_name VARCHAR(100)');
        DB::statement('ALTER TABLE AS_Supplier_Info ALTER COLUMN si_contact_person VARCHAR(10)');
        DB::statement('ALTER TABLE AS_Supplier_Info ALTER COLUMN si_address1 VARCHAR(80)');
        DB::statement('ALTER TABLE AS_Supplier_Info ALTER COLUMN si_address2 VARCHAR(80)');
        DB::statement('ALTER TABLE AS_Supplier_Info ALTER COLUMN si_address3 VARCHAR(80)');
        DB::statement('ALTER TABLE AS_Supplier_Info ALTER COLUMN si_postcode VARCHAR(10)');
        DB::statement('ALTER TABLE AS_Supplier_Info ALTER COLUMN si_city VARCHAR(50)');
        DB::statement('ALTER TABLE AS_Supplier_Info ALTER COLUMN si_state VARCHAR(3)');
        DB::statement('ALTER TABLE AS_Supplier_Info ALTER COLUMN si_country VARCHAR(3)');
        DB::statement('ALTER TABLE AS_Supplier_Info ALTER COLUMN si_phone VARCHAR(20)');
        DB::statement('ALTER TABLE AS_Supplier_Info ALTER COLUMN si_fax VARCHAR(20)');
        DB::statement('ALTER TABLE AS_Supplier_Info ALTER COLUMN si_email VARCHAR(100)');
        DB::statement('ALTER TABLE AS_Supplier_Info ALTER COLUMN si_mgsp_code VARCHAR(15)');
        DB::statement('ALTER TABLE AS_Supplier_Info ALTER COLUMN si_create_id VARCHAR(20)');
        DB::statement('ALTER TABLE AS_Supplier_Info ALTER COLUMN si_update_id VARCHAR(20)');
        DB::statement('ALTER TABLE AS_Supplier_Info ALTER COLUMN si_active_sts VARCHAR(1)');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('AS_Supplier_Info');
    }
};
