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
        Schema::create('HD_Holiday_Calendar', function (Blueprint $table) {

            $table->string('Hc_Cd',10);  // -- 1
            $table->string('Hc_Dt',50); // -- 2
            $table->string('Hc_Yr',50); // -- 3
            $table->datetime('Hc_DateTime')->nullable(); // -- 4
            $table->char('Hc_Cal_Sts_Rec',1); // -- 5
            $table->string('Hc_Create_ID',20); // -- 6
            $table->datetime('Hc_Create_Dt'); // -- 7
            $table->string('Hc_Update_ID',20)->nullable(); // -- 8
            $table->datetime('Hc_Update_Dt')->nullable(); // -- 9
            // -----------------------
            $table->timestamps();
            $table->softDeletes();
        });

        DB::statement('ALTER TABLE HD_Holiday_Calendar ALTER COLUMN Hc_Cd VARCHAR(10)');
        DB::statement('ALTER TABLE HD_Holiday_Calendar ALTER COLUMN Hc_Dt VARCHAR(50)');
        DB::statement('ALTER TABLE HD_Holiday_Calendar ALTER COLUMN Hc_Yr VARCHAR(50)');
        DB::statement('ALTER TABLE HD_Holiday_Calendar ALTER COLUMN Hc_Cal_Sts_Rec CHAR(1)');
        DB::statement('ALTER TABLE HD_Holiday_Calendar ALTER COLUMN Hc_Create_ID VARCHAR(20)');
        DB::statement('ALTER TABLE HD_Holiday_Calendar ALTER COLUMN Hc_Update_ID VARCHAR(20)');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('HD_Holiday_Calendar');
    }
};
