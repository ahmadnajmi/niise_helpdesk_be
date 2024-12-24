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
        Schema::create('refAction_Access', function (Blueprint $table) {

            $table->string('aa_action_code',20); // -- 1
            $table->char('aa_access_level', 3); // -- 2
            $table->string('aa_create_id',20)->nullable(); // -- 3
            $table->datetime('aa_create_date')->nullable(); // -- 4
            // ---------------------------
            $table->timestamps();
            $table->softDeletes();
        });

        DB::statement('ALTER TABLE refAction_Access ALTER COLUMN aa_action_code VARCHAR(20)');
        DB::statement('ALTER TABLE refAction_Access ALTER COLUMN aa_access_level CHAR(3)');
        DB::statement('ALTER TABLE refAction_Access ALTER COLUMN aa_create_id VARCHAR(20)');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('refAction_Access');
    }
};
