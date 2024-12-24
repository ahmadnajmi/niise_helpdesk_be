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
        Schema::create('refCase_Status', function (Blueprint $table) {

            $table->string('cs_case_sts_code',2)->nullable(); // -- 1
            $table->string('cs_case_sts_desc',50)->nullable(); // -- 2
            $table->string('cs_create_id',20)->nullable(); // -- 3
            $table->datetime('cs_create_date')->nullable(); // -- 4
            $table->string('cs_update_id',20)->nullable(); // -- 5
            $table->datetime('cs_update_date')->nullable(); // -- 6
            $table->char('cs_status_rec',3)->nullable(); // -- 7
            // ------------------------
            $table->timestamps();
            $table->softDeletes();
        });

        DB::statement('ALTER TABLE refCase_Status ALTER COLUMN cs_case_sts_code VARCHAR(2)');
        DB::statement('ALTER TABLE refCase_Status ALTER COLUMN cs_case_sts_desc VARCHAR(50)');
        DB::statement('ALTER TABLE refCase_Status ALTER COLUMN cs_create_id VARCHAR(20)');
        DB::statement('ALTER TABLE refCase_Status ALTER COLUMN cs_update_id VARCHAR(20)');
        DB::statement('ALTER TABLE refCase_Status ALTER COLUMN cs_status_rec CHAR(3)');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('refCase_Status');
    }
};
