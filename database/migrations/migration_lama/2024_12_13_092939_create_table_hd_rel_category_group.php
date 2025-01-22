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
        Schema::create('HD_RelCategory_Group', function (Blueprint $table) {

            $table->string('rcg_log_no',15);  // -- 1
            $table->string('rcg_check_group',10); // -- 2
            $table->char('rcg_category_code',50)->nullable(); // -- 3
            $table->unsignedBigInteger('ID'); // -- 4
            // ----------------------
            $table->timestamps();
            $table->softDeletes();
        });

        DB::statement('ALTER TABLE HD_RelCategory_Group ALTER COLUMN rcg_log_no VARCHAR(15)');
        DB::statement('ALTER TABLE HD_RelCategory_Group ALTER COLUMN rcg_check_group VARCHAR(10)');
        DB::statement('ALTER TABLE HD_RelCategory_Group ALTER COLUMN rcg_category_code CHAR(50)');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('HD_RelCategory_Group');
    }
};
