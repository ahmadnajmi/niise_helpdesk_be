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
        Schema::create('refBizHour', function (Blueprint $table) {

            $table->string('bh_bizhour_code',4);  // -- 1
            $table->string('bh_bizhour_desc',50); // -- 2
            $table->string('bh_normal_start_time',6)->nullable(); // -- 3
            $table->string('bh_normal_end_time',6)->nullable(); // -- 4
            $table->decimal('bh_normal_tothours', 4, 1)->nullable(); // -- 5
            $table->string('bh_half_day',50)->nullable(); // -- 6
            $table->string('bh_hday_start_time',6)->nullable(); // -- 7
            $table->string('bh_hday_end_time',6)->nullable(); // -- 8
            $table->decimal('bh_hday_tothours', 4, 1)->nullable(); // -- 9
            $table->string('bh_weekend',50)->nullable(); // -- 10
            $table->string('bh_nonworking_day',50)->nullable(); // -- 11
            $table->string('bh_create_id',20)->nullable(); // -- 12
            $table->datetime('bh_create_date')->nullable(); // -- 13
            $table->string('bh_update_id',20)->nullable(); // -- 14
            $table->datetime('bh_update_date')->nullable(); // -- 15
            $table->string('bh_status_rec',3)->nullable(); // -- 16
            // ----------------------
            $table->timestamps();
            $table->softDeletes();
        });

        DB::statement('ALTER TABLE refBizHour ALTER COLUMN bh_bizhour_code VARCHAR(4)');
        DB::statement('ALTER TABLE refBizHour ALTER COLUMN bh_bizhour_desc VARCHAR(50)');
        DB::statement('ALTER TABLE refBizHour ALTER COLUMN bh_normal_start_time VARCHAR(6)');
        DB::statement('ALTER TABLE refBizHour ALTER COLUMN bh_normal_end_time VARCHAR(6)');
        DB::statement('ALTER TABLE refBizHour ALTER COLUMN bh_half_day VARCHAR(50)');
        DB::statement('ALTER TABLE refBizHour ALTER COLUMN bh_hday_start_time VARCHAR(6)');
        DB::statement('ALTER TABLE refBizHour ALTER COLUMN bh_hday_end_time VARCHAR(6)');
        DB::statement('ALTER TABLE refBizHour ALTER COLUMN bh_weekend VARCHAR(50)');
        DB::statement('ALTER TABLE refBizHour ALTER COLUMN bh_nonworking_day VARCHAR(50)');
        DB::statement('ALTER TABLE refBizHour ALTER COLUMN bh_create_id VARCHAR(20)');
        DB::statement('ALTER TABLE refBizHour ALTER COLUMN bh_update_id VARCHAR(20)');
        DB::statement('ALTER TABLE refBizHour ALTER COLUMN bh_status_rec VARCHAR(3)');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('refBizHour');
    }
};
