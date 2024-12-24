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
        Schema::create('com_status', function (Blueprint $table) {

            $table->string('ID',3)->nullable(false);  // -- 1
            $table->string('DESCRIPTION',255); // -- 2
            $table->string('STS_ID',3); // -- 3
            $table->string('UPD_ID',20)->nullable(); // -- 4
            $table->datetime('UPD_DT')->nullable(); // -- 5
            // --------------------
            $table->timestamps();
            $table->softDeletes();
        });

        DB::statement('ALTER TABLE com_status ALTER COLUMN ID VARCHAR(3)');
        DB::statement('ALTER TABLE com_status ALTER COLUMN DESCRIPTION VARCHAR(255)');
        DB::statement('ALTER TABLE com_status ALTER COLUMN STS_ID VARCHAR(3)');
        DB::statement('ALTER TABLE com_status ALTER COLUMN UPD_ID VARCHAR(20)');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('com_status');
    }
};
