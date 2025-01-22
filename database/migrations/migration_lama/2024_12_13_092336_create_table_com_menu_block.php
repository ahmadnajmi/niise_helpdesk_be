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
        Schema::create('com_menu_block', function (Blueprint $table) {

            $table->string('MENU_ID',5)->nullable(); // -- 1
            $table->string('USR_ID',20)->nullable(); // -- 2
            $table->string('REMARKS',200)->nullable(); // -- 3
            $table->string('UPD_ID',20)->nullable(); // -- 4
            $table->datetime('UPD_DT')->nullable(); // -- 5
            // --------------------
            $table->timestamps();
            $table->softDeletes();
        });

        DB::statement('ALTER TABLE com_menu_block ALTER COLUMN MENU_ID VARCHAR(5)');
        DB::statement('ALTER TABLE com_menu_block ALTER COLUMN USR_ID VARCHAR(20)');
        DB::statement('ALTER TABLE com_menu_block ALTER COLUMN REMARKS VARCHAR(200)');
        DB::statement('ALTER TABLE com_menu_block ALTER COLUMN UPD_ID VARCHAR(20)');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('com_menu_block');
    }
};
