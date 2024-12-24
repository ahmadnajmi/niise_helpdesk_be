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
        Schema::create('refDocument_Type', function (Blueprint $table) {

            $table->char('dt_doc_type',3); // -- 1
            $table->string('dt_doc_desc',50); // -- 2
            $table->string('dt_doc_naming',50); // -- 3
            $table->string('dt_create_id',20)->nullable(); // -- 4
            $table->datetime('dt_create_date')->nullable(); // -- 5
            $table->string('dt_update_id',20)->nullable(); // -- 6
            $table->datetime('dt_update_date')->nullable(); // -- 7
            $table->string('dt_status_rec',3)->nullable(); // -- 8
            // -----------------------
            $table->timestamps();
            $table->softDeletes();
        });

        DB::statement('ALTER TABLE refDocument_Type ALTER COLUMN dt_doc_type CHAR(3)');
        DB::statement('ALTER TABLE refDocument_Type ALTER COLUMN dt_doc_desc VARCHAR(50)');
        DB::statement('ALTER TABLE refDocument_Type ALTER COLUMN dt_doc_naming VARCHAR(50)');
        DB::statement('ALTER TABLE refDocument_Type ALTER COLUMN dt_create_id VARCHAR(20)');
        DB::statement('ALTER TABLE refDocument_Type ALTER COLUMN dt_update_id VARCHAR(20)');
        DB::statement('ALTER TABLE refDocument_Type ALTER COLUMN dt_status_rec CHAR(3)');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('refDocument_Type');
    }
};
