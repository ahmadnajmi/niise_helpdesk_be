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
        Schema::create('refMainCategory', function (Blueprint $table) {

            // what is this?
            $table->integer('Ct_Code')->nullable(); // -- 1
            $table->string('Ct_Abbreviation',50)->nullable(); // -- 2
            // ----------------------
            $table->timestamps();
            $table->softDeletes();
        });

        DB::statement('ALTER TABLE refMainCategory ALTER COLUMN Ct_Abbreviation VARCHAR(50)');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('refMainCategory');
    }
};
