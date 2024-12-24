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
        Schema::create('refState_Weekend', function (Blueprint $table) {

            $table->char('sw_Stt_Wknd_Cd',3); // -- 1
            $table->char('sw_Wknd_Cd',1)->nullable(); // -- 2
            $table->char('sw_Stt_Wknd_Desc',50)->nullable(); // -- 3
            $table->string('sw_create_id',20)->nullable(); // -- 4
            $table->datetime('sw_create_date')->nullable(); // -- 5
            $table->string('sw_update_id',20)->nullable(); // -- 6
            $table->datetime('sw_update_date')->nullable(); // -- 7
            // ----------------------
            $table->timestamps();
            $table->softDeletes();
        });

        DB::statement('ALTER TABLE refState_Weekend ALTER COLUMN sw_Stt_Wknd_Cd CHAR(3)');
        DB::statement('ALTER TABLE refState_Weekend ALTER COLUMN sw_Wknd_Cd CHAR(1)');
        DB::statement('ALTER TABLE refState_Weekend ALTER COLUMN sw_Stt_Wknd_Desc CHAR(50)');
        DB::statement('ALTER TABLE refState_Weekend ALTER COLUMN sw_create_id VARCHAR(20)');
        DB::statement('ALTER TABLE refState_Weekend ALTER COLUMN sw_update_id VARCHAR(20)');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('refState_Weekend');
    }
};
