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
        Schema::create('HD_Known_Error', function (Blueprint $table) {

            $table->string('ke_ID',15); // -- 1
            $table->string('ke_customer_id',10)->nullable(); // -- 2
            $table->string('ke_category',50)->nullable(); // -- 3
            $table->string('ke_keyword',1000); // -- 4
            $table->string('ke_problem',1000); // -- 5
            $table->string('ke_resolution',1500)->nullable(); // -- 6
            $table->char('ke_status',1); // -- 7
            $table->string('ke_create_id',20)->nullable(); // -- 8
            $table->datetime('ke_create_date')->nullable(); // -- 9
            $table->string('ke_update_id',20)->nullable(); // -- 10
            $table->datetime('ke_update_date')->nullable(); // -- 11
            // ----------------------
            $table->timestamps();
            $table->softDeletes();
        });

        DB::statement('ALTER TABLE HD_Known_Error ALTER COLUMN ke_ID VARCHAR(15)');
        DB::statement('ALTER TABLE HD_Known_Error ALTER COLUMN ke_customer_id VARCHAR(10)');
        DB::statement('ALTER TABLE HD_Known_Error ALTER COLUMN ke_category VARCHAR(50)');
        DB::statement('ALTER TABLE HD_Known_Error ALTER COLUMN ke_keyword VARCHAR(1000)');
        DB::statement('ALTER TABLE HD_Known_Error ALTER COLUMN ke_problem VARCHAR(1000)');
        DB::statement('ALTER TABLE HD_Known_Error ALTER COLUMN ke_resolution VARCHAR(1500)');
        DB::statement('ALTER TABLE HD_Known_Error ALTER COLUMN ke_status CHAR(1)');
        DB::statement('ALTER TABLE HD_Known_Error ALTER COLUMN ke_create_id VARCHAR(20)');
        DB::statement('ALTER TABLE HD_Known_Error ALTER COLUMN ke_update_id VARCHAR(20)');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('HD_Known_Error');
    }
};
