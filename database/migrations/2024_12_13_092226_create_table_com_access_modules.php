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
        Schema::create('com_access_modules', function (Blueprint $table) {

            $table->string('ACCESS_ID', 20); // -- 1
            $table->string('MOD_ID', 10); // -- 2
            $table->string('UPD_ID',20)->nullable(); // -- 3
            $table->datetime('UPD_DT')->nullable(); // -- 4
            // ----------------------
            $table->timestamps();
            $table->softDeletes();
        });

        DB::statement('ALTER TABLE com_access_modules ALTER COLUMN ACCESS_ID VARCHAR(20)');
        DB::statement('ALTER TABLE com_access_modules ALTER COLUMN MOD_ID VARCHAR(10)');
        DB::statement('ALTER TABLE com_access_modules ALTER COLUMN UPD_ID VARCHAR(20)');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('com_access_modules');
    }
};
