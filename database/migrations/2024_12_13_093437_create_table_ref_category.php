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
        Schema::create('refCategory', function (Blueprint $table) {

            $table->string('Ct_Code',50)->nullable(); // -- 1
            $table->integer('Ct_Level'); // -- 2
            $table->string('Ct_Abbreviation',15); // -- 3
            $table->string('Ct_Description',254); // -- 4
            $table->string('Ct_Parent',50); // -- 5
            $table->string('Ct_Create_ID',20)->nullable(); // -- 6
            $table->datetime('Ct_Create_Date')->nullable(); // -- 7
            $table->string('Ct_Update_ID',20)->nullable(); // -- 8
            $table->datetime('Ct_Update_Date')->nullable(); // -- 9
            $table->char('Ct_Status_Rec',3)->nullable(); // -- 10
            $table->string('Ct_Priority',3)->nullable(); // -- 11
            // -----------------------
            $table->timestamps();
            $table->softDeletes();
        });

        DB::statement('ALTER TABLE refCategory ALTER COLUMN Ct_Code VARCHAR(50)');
        DB::statement('ALTER TABLE refCategory ALTER COLUMN Ct_Abbreviation VARCHAR(15)');
        DB::statement('ALTER TABLE refCategory ALTER COLUMN Ct_Description VARCHAR(254)');
        DB::statement('ALTER TABLE refCategory ALTER COLUMN Ct_Parent VARCHAR(50)');
        DB::statement('ALTER TABLE refCategory ALTER COLUMN Ct_Create_ID VARCHAR(20)');
        DB::statement('ALTER TABLE refCategory ALTER COLUMN Ct_Update_ID VARCHAR(20)');
        DB::statement('ALTER TABLE refCategory ALTER COLUMN Ct_Status_Rec CHAR(3)');
        DB::statement('ALTER TABLE refCategory ALTER COLUMN Ct_Priority VARCHAR(3)');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('refCategory');
    }
};
