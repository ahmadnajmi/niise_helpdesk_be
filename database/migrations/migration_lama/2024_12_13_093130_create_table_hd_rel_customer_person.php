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
        Schema::create('HD_RelCust_Person', function (Blueprint $table) {

            $table->string('rcp_customer_id',10)->nullable();  // -- 1
            $table->string('rcp_project_mgr_id',10)->nullable(); // -- 2
            $table->unsignedBigInteger('ID')->nullable(); // -- 3
            // ----------------------
            $table->timestamps();
            $table->softDeletes();
        });

        DB::statement('ALTER TABLE HD_RelCust_Person ALTER COLUMN rcp_customer_id VARCHAR(10)');
        DB::statement('ALTER TABLE HD_RelCust_Person ALTER COLUMN rcp_project_mgr_id VARCHAR(10)');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('HD_RelCust_Person');
    }
};
