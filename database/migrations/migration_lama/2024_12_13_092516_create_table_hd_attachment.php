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
        Schema::create('HD_Attachment', function (Blueprint $table) {

            $table->string('at_doc_id',20); // -- 1
            $table->string('at_doc_Owner',10); // -- 2
            $table->string('at_owner_type',1); // -- 3
            $table->string('at_doc_type',3); // -- 4
            $table->string('at_filename',200); // -- 5
            $table->string('at_file_location',50); // -- 6
            $table->string('at_doc_status',1); // -- 7
            $table->string('at_create_id',20)->nullable(); // -- 8
            $table->datetime('at_create_date')->nullable(); // -- 9
            $table->string('at_update_id',20)->nullable(); // -- 10
            $table->datetime('at_update_date')->nullable(); // -- 11
            // -------------------
            $table->timestamps();
            $table->softDeletes();
        });

        DB::statement('ALTER TABLE HD_Attachment ALTER COLUMN at_doc_id VARCHAR(20)');
        DB::statement('ALTER TABLE HD_Attachment ALTER COLUMN at_doc_Owner VARCHAR(10)');
        DB::statement('ALTER TABLE HD_Attachment ALTER COLUMN at_owner_type VARCHAR(1)');
        DB::statement('ALTER TABLE HD_Attachment ALTER COLUMN at_doc_type VARCHAR(3)');
        DB::statement('ALTER TABLE HD_Attachment ALTER COLUMN at_filename VARCHAR(200)');
        DB::statement('ALTER TABLE HD_Attachment ALTER COLUMN at_file_location VARCHAR(50)');
        DB::statement('ALTER TABLE HD_Attachment ALTER COLUMN at_doc_status VARCHAR(1)');
        DB::statement('ALTER TABLE HD_Attachment ALTER COLUMN at_create_id VARCHAR(20)');
        DB::statement('ALTER TABLE HD_Attachment ALTER COLUMN at_update_id VARCHAR(20)');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('HD_Attachment');
    }
};
