<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement('ALTER TABLE INCIDENTS ADD (information_temp CLOB)');

        DB::statement('UPDATE INCIDENTS SET information_temp = TO_CLOB(information)');

        DB::statement('ALTER TABLE INCIDENTS DROP COLUMN information');

        DB::statement('ALTER TABLE INCIDENTS RENAME COLUMN information_temp TO information');

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
       
    }
};
