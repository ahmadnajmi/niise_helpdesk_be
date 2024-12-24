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
        Schema::create('refAction', function (Blueprint $table) {

            $table->string('ac_code',10)->nullable();  // -- 1
            $table->string('ac_abbreviation',10)->nullable(); // -- 2
            $table->string('ac_description1',50); // -- 3
            $table->string('ac_description2',300); // -- 4
            $table->string('ac_category',10)->nullable(); // -- 5
            $table->string('ac_create_id',20)->nullable(); // -- 6
            $table->datetime('ac_create_date')->nullable(); // -- 7
            $table->string('ac_update_id',20)->nullable(); // -- 8
            $table->datetime('ac_update_date')->nullable(); // -- 9
            $table->string('ac_status_rec',3)->nullable(); // -- 10
            $table->string('ac_value',100)->nullable(); // -- 11
            $table->string('ac_type',1)->nullable(); // -- 12
            $table->string('ac_email_status',3); // -- 13
            $table->string('ac_email_recipients',10)->nullable(); // -- 14
            // -----------------------
            $table->timestamps();
            $table->softDeletes();
        });

        DB::statement('ALTER TABLE refAction ALTER COLUMN ac_code VARCHAR(10)');
        DB::statement('ALTER TABLE refAction ALTER COLUMN ac_abbreviation VARCHAR(10)');
        DB::statement('ALTER TABLE refAction ALTER COLUMN ac_description1 VARCHAR(50)');
        DB::statement('ALTER TABLE refAction ALTER COLUMN ac_description2 VARCHAR(300)');
        DB::statement('ALTER TABLE refAction ALTER COLUMN ac_category VARCHAR(10)');
        DB::statement('ALTER TABLE refAction ALTER COLUMN ac_create_id VARCHAR(20)');
        DB::statement('ALTER TABLE refAction ALTER COLUMN ac_update_id VARCHAR(20)');
        DB::statement('ALTER TABLE refAction ALTER COLUMN ac_status_rec VARCHAR(3)');
        DB::statement('ALTER TABLE refAction ALTER COLUMN ac_value VARCHAR(100)');
        DB::statement('ALTER TABLE refAction ALTER COLUMN ac_type VARCHAR(1)');
        DB::statement('ALTER TABLE refAction ALTER COLUMN ac_email_status VARCHAR(3)');
        DB::statement('ALTER TABLE refAction ALTER COLUMN ac_email_recipients VARCHAR(10)');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('refAction');
    }
};
