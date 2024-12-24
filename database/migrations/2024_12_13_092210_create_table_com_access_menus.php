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
        Schema::create('com_access_menus', function (Blueprint $table) {

            $table->string('ACCESS_ID', 20)->nullable(); // -- 1
            $table->string('MENU_ID', 20)->nullable(); // -- 2
            $table->string('MOD_ID', 20)->nullable(); // -- 3
            $table->string('UPD_ID',20)->nullable(); // -- 4
            $table->datetime('UPD_DT')->nullable(); // -- 5
            // ----------------------
            $table->timestamps();
            $table->softDeletes();
        });

        DB::statement('ALTER TABLE com_access_menus ALTER COLUMN ACCESS_ID VARCHAR(20)');
        DB::statement('ALTER TABLE com_access_menus ALTER COLUMN MENU_ID VARCHAR(20)');
        DB::statement('ALTER TABLE com_access_menus ALTER COLUMN MOD_ID VARCHAR(20)');
        DB::statement('ALTER TABLE com_access_menus ALTER COLUMN UPD_ID VARCHAR(20)');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('com_access_menus');
    }
};
