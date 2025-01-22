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
        Schema::create('com_config', function (Blueprint $table) {

            $table->string('ID', 5); // -- 1
            $table->string('CFG_NAME', 50)->nullable(); // -- 2
            $table->string('CFG_VAL',255)->nullable(); // -- 3
            $table->string('CFG_IS_ON',1)->nullable(); // -- 4
            $table->string('CFG_REMARKS',255)->nullable(); // -- 5
            $table->string('UPD_ID',20)->nullable(); // -- 6
            $table->datetime('UPD_DT')->nullable(); // -- 7
            // --------------------------
            $table->timestamps();
            $table->softDeletes();
        });

        DB::statement('ALTER TABLE com_config ALTER COLUMN ID VARCHAR(5)');
        DB::statement('ALTER TABLE com_config ALTER COLUMN CFG_NAME VARCHAR(50)');
        DB::statement('ALTER TABLE com_config ALTER COLUMN CFG_VAL VARCHAR(255)');
        DB::statement('ALTER TABLE com_config ALTER COLUMN CFG_IS_ON VARCHAR(1)');
        DB::statement('ALTER TABLE com_config ALTER COLUMN CFG_REMARKS VARCHAR(255)');
        DB::statement('ALTER TABLE com_config ALTER COLUMN UPD_ID VARCHAR(20)');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('com_config');
    }
};
