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
        Schema::create('HD_SLA', function (Blueprint $table) {

            $table->string('sl_sla_code',50);  // -- 1
            $table->string('sl_customer_id',10); // -- 2
            $table->datetime('sl_start_date'); // -- 3
            $table->datetime('sl_end_date')->nullable(); // -- 4
            $table->string('sl_category',50)->nullable(); // -- 5
            $table->char('sl_severity_lvl',1)->nullable(); // -- 6
            $table->integer('sl_escalation_time')->nullable(); // -- 7
            $table->integer('sl_resp_to_user')->nullable(); // -- 8
            $table->integer('sl_onsite_resp')->nullable(); // -- 9
            $table->integer('sl_feedback_b4bypass')->nullable(); // -- 10
            $table->integer('sl_feedback_afterbypass')->nullable(); // -- 11
            $table->integer('sl_bypass_timeframe')->nullable(); // -- 12
            $table->integer('sl_due_date_timeframe')->nullable(); // -- 13
            $table->decimal('sl_service_lvl', 5, 2)->nullable(); // -- 14
            $table->datetime('sl_ext_start_end')->nullable(); // -- 15
            $table->datetime('sl_ext_end_date')->nullable(); // -- 16
            $table->string('sl_create_id',20); // -- 17
            $table->datetime('sl_create_date'); // -- 18
            $table->string('sl_update_id',20)->nullable(); // -- 19
            $table->datetime('sl_update_date')->nullable(); // -- 20
            $table->string('sl_group_id_email',50)->nullable(); // -- 21
            $table->string('sl_group_id_sms',50)->nullable(); // -- 22
            $table->char('sl_status_rec',3)->nullable(); // -- 23
            $table->char('sl_template_code',15)->nullable(); // -- 24
            $table->string('sl_code_temp',50)->nullable(); // -- 25
            // ----------------------
            $table->timestamps();
            $table->softDeletes();
        });

        DB::statement('ALTER TABLE HD_SLA ALTER COLUMN sl_sla_code VARCHAR(50)');
        DB::statement('ALTER TABLE HD_SLA ALTER COLUMN sl_customer_id VARCHAR(10)');


        DB::statement('ALTER TABLE HD_SLA ALTER COLUMN sl_category VARCHAR(50)');
        DB::statement('ALTER TABLE HD_SLA ALTER COLUMN sl_severity_lvl CHAR(1)');










        DB::statement('ALTER TABLE HD_SLA ALTER COLUMN sl_create_id VARCHAR(20)');

        DB::statement('ALTER TABLE HD_SLA ALTER COLUMN sl_update_id VARCHAR(20)');

        DB::statement('ALTER TABLE HD_SLA ALTER COLUMN sl_group_id_email VARCHAR(50)');
        DB::statement('ALTER TABLE HD_SLA ALTER COLUMN sl_group_id_sms VARCHAR(50)');
        DB::statement('ALTER TABLE HD_SLA ALTER COLUMN sl_status_rec CHAR(3)');
        DB::statement('ALTER TABLE HD_SLA ALTER COLUMN sl_template_code CHAR(15)');
        DB::statement('ALTER TABLE HD_SLA ALTER COLUMN sl_code_temp VARCHAR(50)');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('HD_SLA');
    }
};
