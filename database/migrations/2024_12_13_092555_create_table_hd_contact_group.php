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
        Schema::create('HD_Contact_Group', function (Blueprint $table) {

            $table->string('cg_group_id',50); // -- 1
            $table->string('cg_personal_id',10); // -- 2
            $table->char('cg_group_type',1); // -- 3
            $table->string('cg_create_id',20); // -- 4
            $table->datetime('cg_create_date'); // -- 5
            $table->unsignedBigInteger('ID')->primary(); // -- 6
            // ----------------------
            $table->timestamps();
            $table->softDeletes();
        });

        DB::statement('ALTER TABLE HD_Contact_Group ALTER COLUMN cg_group_id VARCHAR(50)');
        DB::statement('ALTER TABLE HD_Contact_Group ALTER COLUMN cg_personal_id VARCHAR(10)');
        DB::statement('ALTER TABLE HD_Contact_Group ALTER COLUMN cg_group_type CHAR(1)');
        DB::statement('ALTER TABLE HD_Contact_Group ALTER COLUMN cg_create_id VARCHAR(20)');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('HD_Contact_Group');
    }
};
