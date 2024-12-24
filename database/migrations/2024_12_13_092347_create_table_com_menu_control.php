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
        Schema::create('com_menu_control', function (Blueprint $table) {

            $table->char('ID',5); // -- 1
            $table->string('DESCRIPTION',255)->nullable(); // -- 2
            $table->char('TYPE_ID',1)->nullable(); // -- 3
            $table->string('ACTION_PATH',255)->nullable(); // -- 4
            $table->string('MOD_ID',255)->nullable(); // -- 5
            $table->char('TXN_ID',6)->nullable(); // -- 6
            $table->string('ALLOWABLE',255)->nullable(); // -- 7
            $table->char('STS_ID',3)->nullable(); // -- 8
            $table->string('UPD_ID',20)->nullable(); // -- 9
            $table->datetime('UPD_DT')->nullable(); // -- 10
            // ----------------------
            $table->timestamps();
            $table->softDeletes();
        });

        DB::statement('ALTER TABLE com_menu_control ALTER COLUMN ID CHAR(5)');
        DB::statement('ALTER TABLE com_menu_control ALTER COLUMN DESCRIPTION VARCHAR(255)');
        DB::statement('ALTER TABLE com_menu_control ALTER COLUMN TYPE_ID CHAR(2)');
        DB::statement('ALTER TABLE com_menu_control ALTER COLUMN ACTION_PATH VARCHAR(255)');
        DB::statement('ALTER TABLE com_menu_control ALTER COLUMN MOD_ID VARCHAR(255)');
        DB::statement('ALTER TABLE com_menu_control ALTER COLUMN TXN_ID CHAR(6)');
        DB::statement('ALTER TABLE com_menu_control ALTER COLUMN ALLOWABLE VARCHAR(255)');
        DB::statement('ALTER TABLE com_menu_control ALTER COLUMN STS_ID CHAR(3)');
        DB::statement('ALTER TABLE com_menu_control ALTER COLUMN UPD_ID VARCHAR(20)');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('com_menu_control');
    }
};
