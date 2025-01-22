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
        Schema::create('refHoliday', function (Blueprint $table) {

            $table->string('Hol_Cd',10); // -- 1
            $table->string('Hol_Desc',50); // -- 2
            $table->char('Hol_Sts_Rec',1)->nullable(); // -- 3
            $table->char('Hol_St_Date',10)->nullable(); // -- 4
            $table->char('Hol_End_Date',10)->nullable(); // -- 5
            $table->integer('Hol_NoOfDays')->nullable(); // -- 6
            $table->string('Hol_Create_ID',20)->nullable(); // -- 7
            $table->datetime('Hol_Create_Date')->nullable(); // -- 8
            $table->string('Hol_Update_ID',20)->nullable(); // -- 9
            $table->datetime('Hol_Update_Date')->nullable(); // -- 10
            $table->char('Hol_Status_Rec',1)->nullable(); // -- 11
            // ----------------------
            $table->timestamps();
            $table->softDeletes();
        });

        DB::statement('ALTER TABLE refHoliday ALTER COLUMN Hol_Cd VARCHAR(10)');
        DB::statement('ALTER TABLE refHoliday ALTER COLUMN Hol_Desc VARCHAR(50)');
        DB::statement('ALTER TABLE refHoliday ALTER COLUMN Hol_Sts_Rec CHAR(1)');
        DB::statement('ALTER TABLE refHoliday ALTER COLUMN Hol_St_Date CHAR(10)');
        DB::statement('ALTER TABLE refHoliday ALTER COLUMN Hol_End_Date CHAR(10)');
        DB::statement('ALTER TABLE refHoliday ALTER COLUMN Hol_Create_ID VARCHAR(20)');
        DB::statement('ALTER TABLE refHoliday ALTER COLUMN Hol_Update_ID VARCHAR(20)');
        DB::statement('ALTER TABLE refHoliday ALTER COLUMN Hol_Status_Rec CHAR(1)');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('refHoliday');
    }
};
