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
        Schema::create('com_RelCust_User', function (Blueprint $table) {


            $table->string('rcu_user_id',20); // -- 1
            $table->string('rcu_customer_id',50); // -- 2
            $table->unsignedBigInteger('ID')->primary(); // -- 3
            // ---------------------
            $table->timestamps();
            $table->softDeletes();
        });

        DB::statement('ALTER TABLE com_RelCust_User ALTER COLUMN rcu_user_id VARCHAR(20)');
        DB::statement('ALTER TABLE com_RelCust_User ALTER COLUMN rcu_customer_id VARCHAR(50)');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('com_RelCust_User');
    }
};
