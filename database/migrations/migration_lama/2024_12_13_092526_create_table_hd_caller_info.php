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
        Schema::create('HD_Caller_Info', function (Blueprint $table) {

            $table->string('ci_caller_id',15)->nullable(); // -- 1
            $table->string('ci_customer_id',10)->nullable(); // -- 2
            $table->string('ci_customer_branch',10)->nullable(); // -- 3
            $table->string('ci_name',80)->nullable(); // -- 4
            $table->string('ci_email',100)->nullable(); // -- 5
            $table->string('ci_caller_no',25)->nullable(); // -- 6
            $table->string('ci_office_no',25)->nullable(); // -- 7
            $table->string('ci_ext_no',25)->nullable(); // -- 8
            $table->string('ci_create_id',20)->nullable(); // -- 9
            $table->datetime('ci_create_date')->nullable(); // -- 10
            $table->string('ci_update_id',20)->nullable(); // -- 11
            $table->datetime('ci_update_date')->nullable(); // -- 12
            $table->string('ci_status_rec',3)->nullable(); // -- 13
            // --------------------
            $table->timestamps();
            $table->softDeletes();
        });

        DB::statement('ALTER TABLE HD_Caller_Info ALTER COLUMN ci_caller_id VARCHAR(15)');
        DB::statement('ALTER TABLE HD_Caller_Info ALTER COLUMN ci_customer_id VARCHAR(10)');
        DB::statement('ALTER TABLE HD_Caller_Info ALTER COLUMN ci_customer_branch VARCHAR(10)');
        DB::statement('ALTER TABLE HD_Caller_Info ALTER COLUMN ci_name VARCHAR(80)');
        DB::statement('ALTER TABLE HD_Caller_Info ALTER COLUMN ci_email VARCHAR(100)');
        DB::statement('ALTER TABLE HD_Caller_Info ALTER COLUMN ci_caller_no VARCHAR(25)');
        DB::statement('ALTER TABLE HD_Caller_Info ALTER COLUMN ci_office_no VARCHAR(25)');
        DB::statement('ALTER TABLE HD_Caller_Info ALTER COLUMN ci_ext_no VARCHAR(25)');
        DB::statement('ALTER TABLE HD_Caller_Info ALTER COLUMN ci_create_id VARCHAR(20)');
        DB::statement('ALTER TABLE HD_Caller_Info ALTER COLUMN ci_update_id VARCHAR(20)');
        DB::statement('ALTER TABLE HD_Caller_Info ALTER COLUMN ci_status_rec VARCHAR(3)');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('HD_Caller_Info');
    }
};
