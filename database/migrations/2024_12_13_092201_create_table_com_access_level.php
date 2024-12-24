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
        Schema::create('com_access_level', function (Blueprint $table) {

            $table->string('ID', 3); // -- 1
            $table->string('DESCRIPTION',255)->nullable(); // -- 2
            $table->string('IS_CREATE',1)->nullable(); // -- 3
            $table->string('IS_READ',1)->nullable(); // -- 4
            $table->string('IS_UPDATE',1)->nullable(); // -- 5
            $table->string('IS_DELETE',1)->nullable(); // -- 6
            $table->string('STS_ID',3)->nullable(); // -- 7
            $table->string('UPD_ID',20)->nullable(); // -- 8
            $table->datetime('UPD_DT')->nullable(); // -- 9
            // ----------------------
            $table->timestamps();
            $table->softDeletes();
        });

        DB::statement('ALTER TABLE com_access_level ALTER COLUMN ID VARCHAR(3)');
        DB::statement('ALTER TABLE com_access_level ALTER COLUMN DESCRIPTION VARCHAR(255)');
        DB::statement('ALTER TABLE com_access_level ALTER COLUMN IS_CREATE VARCHAR(1)');
        DB::statement('ALTER TABLE com_access_level ALTER COLUMN IS_READ VARCHAR(1)');
        DB::statement('ALTER TABLE com_access_level ALTER COLUMN IS_UPDATE VARCHAR(1)');
        DB::statement('ALTER TABLE com_access_level ALTER COLUMN IS_DELETE VARCHAR(1)');
        DB::statement('ALTER TABLE com_access_level ALTER COLUMN STS_ID VARCHAR(3)');
        DB::statement('ALTER TABLE com_access_level ALTER COLUMN UPD_ID VARCHAR(20)');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('com_access_level');
    }
};
