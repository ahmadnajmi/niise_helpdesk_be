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
        Schema::create('Dashboard', function (Blueprint $table) {

            $table->string('db_id',25); // -- 1
            $table->string('db_title',100)->nullable(); // -- 2
            $table->string('db_note',500)->nullable(); // -- 3
            $table->string('db_create_id',25)->nullable(); // -- 4
            $table->string('db_date',25)->nullable(); // -- 5
            $table->string('db_update_id',25)->nullable(); // -- 6
            $table->string('db_time',25)->nullable(); // -- 7
            // -------------------------------
            $table->timestamps();
            $table->softDeletes();
        });

        DB::statement('ALTER TABLE Dashboard ALTER COLUMN db_id VARCHAR(25)');
        DB::statement('ALTER TABLE Dashboard ALTER COLUMN db_title VARCHAR(100)');
        DB::statement('ALTER TABLE Dashboard ALTER COLUMN db_note VARCHAR(500)');
        DB::statement('ALTER TABLE Dashboard ALTER COLUMN db_create_id VARCHAR(25)');
        DB::statement('ALTER TABLE Dashboard ALTER COLUMN db_date VARCHAR(25)');
        DB::statement('ALTER TABLE Dashboard ALTER COLUMN db_update_id VARCHAR(25)');
        DB::statement('ALTER TABLE Dashboard ALTER COLUMN db_time VARCHAR(25)');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('Dashboard');
    }
};
