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
        Schema::create('HD_Contact_Master', function (Blueprint $table) {

            $table->string('co_group_id',20); // -- 1
            $table->string('co_description',50); // -- 2
            $table->string('co_customer_id',10); // -- 3
            $table->string('co_state_code',3); // -- 4
            $table->string('co_zone',10); // -- 5
            $table->string('co_create_id',20); // -- 6
            $table->datetime('co_create_date'); // -- 7
            $table->string('co_update_id',20)->nullable(); // -- 8
            $table->datetime('co_update_date')->nullable(); // -- 9
            $table->string('co_active_sts',3); // -- 10
            // ----------------------
            $table->timestamps();
            $table->softDeletes();
        });

        DB::statement('ALTER TABLE HD_Contact_Master ALTER COLUMN co_group_id VARCHAR(20)');
        DB::statement('ALTER TABLE HD_Contact_Master ALTER COLUMN co_description VARCHAR(50)');
        DB::statement('ALTER TABLE HD_Contact_Master ALTER COLUMN co_customer_id VARCHAR(10)');
        DB::statement('ALTER TABLE HD_Contact_Master ALTER COLUMN co_state_code VARCHAR(3)');
        DB::statement('ALTER TABLE HD_Contact_Master ALTER COLUMN co_zone VARCHAR(10)');
        DB::statement('ALTER TABLE HD_Contact_Master ALTER COLUMN co_create_id VARCHAR(20)');
        DB::statement('ALTER TABLE HD_Contact_Master ALTER COLUMN co_update_id VARCHAR(20)');
        DB::statement('ALTER TABLE HD_Contact_Master ALTER COLUMN co_active_sts VARCHAR(3)');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('HD_Contact_Master');
    }
};
