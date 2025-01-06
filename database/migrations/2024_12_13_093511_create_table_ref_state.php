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
        Schema::create('refState', function (Blueprint $table) {

            $table->string('st_state_code',3);  // -- 1
            $table->string('st_state_desc',50); // -- 2
            $table->string('st_state_zone',20)->nullable(); // -- 3
            $table->string('st_create_id',20)->nullable(); // -- 4
            $table->datetime('st_create_date')->nullable(); // -- 5
            $table->string('st_update_id',20)->nullable(); // -- 6
            $table->datetime('st_update_date')->nullable(); // -- 7
            $table->string('st_status_rec',1)->nullable(); // -- 8
            // -------------------------
            $table->timestamps();
            $table->softDeletes();
        });

        DB::statement('ALTER TABLE refState ALTER COLUMN st_state_code VARCHAR(3)');
        DB::statement('ALTER TABLE refState ALTER COLUMN st_state_desc VARCHAR(50)');
        DB::statement('ALTER TABLE refState ALTER COLUMN st_state_zone VARCHAR(20)');
        DB::statement('ALTER TABLE refState ALTER COLUMN st_create_id VARCHAR(20)');
        DB::statement('ALTER TABLE refState ALTER COLUMN st_update_id VARCHAR(20)');
        DB::statement('ALTER TABLE refState ALTER COLUMN st_status_rec VARCHAR(1)');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('refState');
    }
};
