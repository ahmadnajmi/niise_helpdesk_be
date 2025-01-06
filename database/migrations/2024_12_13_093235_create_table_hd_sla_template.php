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
        Schema::create('HD_SLA_Template', function (Blueprint $table) {

            $table->string('st_code',15); // -- 1
            $table->char('st_severity_lvl',1)->nullable(); // -- 2
            $table->integer('st_escalation_time')->nullable(); // -- 3
            $table->integer('st_resp_to_user')->nullable(); // -- 4
            $table->integer('st_onsite_resp')->nullable(); // -- 5
            $table->integer('st_feedback_b4bypass')->nullable(); // -- 6
            $table->integer('st_feedback_afterbypass')->nullable(); // -- 7
            $table->integer('st_bypass_timeframe')->nullable(); // -- 8
            $table->integer('st_due_date_timeframe')->nullable(); // -- 9
            $table->decimal('st_service_lvl', 3, 0)->nullable(); // -- 10
            $table->string('st_create_id',20)->nullable(); // -- 11
            $table->datetime('st_create_date')->nullable(); // -- 12
            $table->string('st_update_id',20)->nullable(); // -- 13
            $table->datetime('st_update_date')->nullable(); // -- 14
            $table->string('st_remarks',500)->nullable(); // -- 15
            // ----------------------
            $table->timestamps();
            $table->softDeletes();
        });

        DB::statement('ALTER TABLE HD_SLA_Template ALTER COLUMN st_code VARCHAR(15)');
        DB::statement('ALTER TABLE HD_SLA_Template ALTER COLUMN st_severity_lvl CHAR(1)');
        DB::statement('ALTER TABLE HD_SLA_Template ALTER COLUMN st_create_id VARCHAR(20)');
        DB::statement('ALTER TABLE HD_SLA_Template ALTER COLUMN st_update_id VARCHAR(20)');
        DB::statement('ALTER TABLE HD_SLA_Template ALTER COLUMN st_remarks VARCHAR(500)');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('HD_SLA_Template');
    }
};
