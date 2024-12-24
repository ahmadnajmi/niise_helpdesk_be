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
        Schema::create('HD_RelGroup_Customer', function (Blueprint $table) {

            $table->string('gc_group_id',20);  // -- 1
            $table->string('gc_customer_id',10); // -- 2
            $table->unsignedBigInteger('ID')->primary(); // -- 3
            // -----------------------
            $table->timestamps();
            $table->softDeletes();
        });

        DB::statement('ALTER TABLE HD_RelGroup_Customer ALTER COLUMN gc_group_id VARCHAR(20)');
        DB::statement('ALTER TABLE HD_RelGroup_Customer ALTER COLUMN gc_customer_id VARCHAR(10)');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('HD_RelGroup_Customer');
    }
};
