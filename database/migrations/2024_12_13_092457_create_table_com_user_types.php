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
        Schema::create('com_user_types', function (Blueprint $table) {

            $table->char('ID',3);  // -- 1
            $table->string('DESCRIPTION',255); // -- 2
            $table->char('STS_ID',3); // -- 3
            $table->string('UPD_ID',20)->nullable(); // -- 4
            $table->char('UPD_DT', 19)->nullable(); // -- 5
            // --------------------
            $table->timestamps();
            $table->softDeletes();
        });

        DB::statement('ALTER TABLE com_user_types ALTER COLUMN ID CHAR(3)');
        DB::statement('ALTER TABLE com_user_types ALTER COLUMN DESCRIPTION VARCHAR(255)');
        DB::statement('ALTER TABLE com_user_types ALTER COLUMN STS_ID CHAR(3)');
        DB::statement('ALTER TABLE com_user_types ALTER COLUMN UPD_ID VARCHAR(20)');
        DB::statement('ALTER TABLE com_user_types ALTER COLUMN UPD_DT CHAR(19)');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('com_user_types');
    }
};
