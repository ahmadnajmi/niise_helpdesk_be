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
        Schema::create('com_modules', function (Blueprint $table) {

            $table->string('ID',3); // -- 1
            $table->string('DESCRIPTION',255)->nullable();  // -- 2
            $table->string('ICON',50)->nullable(); // -- 3
            $table->string('IS_DEFAULT',1); // -- 4
            $table->string('STS_ID',3)->nullable(); // -- 5
            $table->string('UPD_ID',20)->nullable(); // -- 6
            $table->datetime('UPD_DT')->nullable(); // -- 7
            // ----------------------
            $table->timestamps();
            $table->softDeletes();
        });

        DB::statement('ALTER TABLE com_modules ALTER COLUMN ID VARCHAR(3)');
        DB::statement('ALTER TABLE com_modules ALTER COLUMN DESCRIPTION VARCHAR(255)');
        DB::statement('ALTER TABLE com_modules ALTER COLUMN ICON VARCHAR(50)');
        DB::statement('ALTER TABLE com_modules ALTER COLUMN IS_DEFAULT VARCHAR(1)');
        DB::statement('ALTER TABLE com_modules ALTER COLUMN STS_ID VARCHAR(3)');
        DB::statement('ALTER TABLE com_modules ALTER COLUMN UPD_ID VARCHAR(20)');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('com_modules');
    }
};
