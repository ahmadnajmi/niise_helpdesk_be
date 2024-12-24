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
        Schema::create('HD_RelCase_Attachment', function (Blueprint $table) {

            $table->string('rca_log_no',15); // -- 1
            $table->string('rca_file_id',20); // -- 2
            $table->string('rca_file_name',200)->nullable(); // -- 3
            $table->string('rca_file_location',50)->nullable(); // -- 4
            // -------------------------
            $table->timestamps();
            $table->softDeletes();
        });

        DB::statement('ALTER TABLE HD_RelCase_Attachment ALTER COLUMN rca_log_no VARCHAR(15)');
        DB::statement('ALTER TABLE HD_RelCase_Attachment ALTER COLUMN rca_file_id VARCHAR(20)');
        DB::statement('ALTER TABLE HD_RelCase_Attachment ALTER COLUMN rca_file_name VARCHAR(200)');
        DB::statement('ALTER TABLE HD_RelCase_Attachment ALTER COLUMN rca_file_location VARCHAR(50)');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('HD_RelCase_Attachment');
    }
};
