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
        Schema::create('com_audit_trails', function (Blueprint $table) {

            $table->string('AU_LOG_NO',50)->nullable(); // -- 1
            $table->datetime('AUDATETIME')->nullable(); // -- 2
            $table->string('USER_ID',50)->nullable(); // -- 4
            $table->string('TXN_DETAIL',1000)->nullable(); // -- 5 // Initial value: 5000
            $table->string('INC_DESC',1000)->nullable(); // -- 6 // Initial value: 5000
            $table->string('HD_ACTION',50)->nullable(); // -- 7
            $table->unsignedBigInteger('ID'); // -- 8
            // -------------------
            $table->timestamps();
            $table->softDeletes();
        });

        DB::statement('ALTER TABLE com_audit_trails ALTER COLUMN AU_LOG_NO VARCHAR(50)');
        DB::statement('ALTER TABLE com_audit_trails ALTER COLUMN USER_ID VARCHAR(50)');
        DB::statement('ALTER TABLE com_audit_trails ALTER COLUMN TXN_DETAIL VARCHAR(5000)');
        DB::statement('ALTER TABLE com_audit_trails ALTER COLUMN INC_DESC VARCHAR(5000)');
        DB::statement('ALTER TABLE com_audit_trails ALTER COLUMN HD_ACTION VARCHAR(50)');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('com_audit_trails');
    }
};
