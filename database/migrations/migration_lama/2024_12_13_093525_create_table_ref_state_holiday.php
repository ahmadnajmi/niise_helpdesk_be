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
        Schema::create('refState_Holiday', function (Blueprint $table) {

            $table->string('sh_Stt_Hol_Cd',15); // -- 1
            $table->string('sh_Hol_Cd',15); // -- 2
            $table->char('sh_Stt_Hol_Sts_Rec',1)->nullable(); // -- 3
            $table->string('sh_Create_ID',20)->nullable(); // -- 4
            $table->datetime('sh_Create_Dt')->nullable(); // -- 5
            $table->string('sh_Update_ID',20)->nullable(); // -- 6
            $table->datetime('sh_Update_Dt')->nullable(); // -- 7
            $table->char('sh_Status_Rec',1)->nullable(); // -- 8
            // ---------------------
            $table->timestamps();
            $table->softDeletes();
        });

        DB::statement('ALTER TABLE refState_Holiday ALTER COLUMN sh_Stt_Hol_Cd VARCHAR(15)');
        DB::statement('ALTER TABLE refState_Holiday ALTER COLUMN sh_Hol_Cd VARCHAR(15)');
        DB::statement('ALTER TABLE refState_Holiday ALTER COLUMN sh_Stt_Hol_Sts_Rec CHAR(1)');
        DB::statement('ALTER TABLE refState_Holiday ALTER COLUMN sh_Create_ID VARCHAR(20)');
        DB::statement('ALTER TABLE refState_Holiday ALTER COLUMN sh_Update_ID VARCHAR(20)');
        DB::statement('ALTER TABLE refState_Holiday ALTER COLUMN sh_Status_Rec CHAR(1)');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('refState_Holiday');
    }
};
