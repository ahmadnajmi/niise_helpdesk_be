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
        Schema::create('AS_Asset_Warranty', function (Blueprint $table) {

            $table->string('aw_SN_No',30); // -- 1
            $table->string('aw_customer_id',10)->nullable(); // -- 2
            $table->string('aw_branch_code',10)->nullable(); // -- 3
            $table->datetime('aw_create_date'); // -- 4
            $table->string('aw_owned_by',10)->nullable(); // -- 5
            $table->string('aw_vendor_id',10)->nullable(); // -- 6
            $table->datetime('aw_effective_date')->nullable(); // -- 7
            $table->datetime('aw_expiry_date')->nullable(); // -- 8
            $table->datetime('aw_maint_start')->nullable(); // -- 9
            $table->datetime('aw_maint_end')->nullable(); // -- 10
            $table->string('aw_create_id',20); // -- 11
            $table->string('aw_update_id',20)->nullable(); // -- 12
            $table->datetime('aw_update_date')->nullable(); // -- 13
            // ---------------------
            $table->timestamps();
            $table->softDeletes();
        });

        DB::statement('ALTER TABLE AS_Asset_Warranty ALTER COLUMN aw_SN_No VARCHAR(30)');
        DB::statement('ALTER TABLE AS_Asset_Warranty ALTER COLUMN aw_customer_id VARCHAR(10)');
        DB::statement('ALTER TABLE AS_Asset_Warranty ALTER COLUMN aw_branch_code VARCHAR(10)');
        DB::statement('ALTER TABLE AS_Asset_Warranty ALTER COLUMN aw_owned_by VARCHAR(10)');
        DB::statement('ALTER TABLE AS_Asset_Warranty ALTER COLUMN aw_vendor_id VARCHAR(10)');
        DB::statement('ALTER TABLE AS_Asset_Warranty ALTER COLUMN aw_create_id VARCHAR(20)');
        DB::statement('ALTER TABLE AS_Asset_Warranty ALTER COLUMN aw_update_id VARCHAR(20)');

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('AS_Asset_Warranty');
    }
};
