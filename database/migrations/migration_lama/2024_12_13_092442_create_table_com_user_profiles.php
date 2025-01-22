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
        Schema::create('com_user_profiles', function (Blueprint $table) {

            $table->string('ID',20); // -- 1
            $table->string('PWD',100)->nullable(); // -- 2
            $table->string('NICK_NAME',100)->nullable(); // -- 3
            $table->integer('PWD_INVALID_HIT')->nullable(); // -- 4
            $table->string('PWD_CREATE_DT',19)->nullable(); // -- 5
            $table->string('INSTITUTION_ID',10)->nullable(); // -- 6
            $table->string('ACL_ID',3)->nullable(); // -- 7
            $table->string('LOGIN_DT',19)->nullable(); // -- 8
            $table->string('LOGIN_STS_ID',1)->nullable(); // -- 9
            $table->string('STS_ID',3)->nullable(); // -- 10
            $table->string('UPD_ID',20)->nullable(); // -- 11
            $table->string('UPD_DT',19)->nullable(); // -- 12
            $table->string('EMAIL_ID',50)->nullable(); // -- 13
            $table->string('PHONE_NO_ID',25)->nullable(); // -- 14
            $table->string('HP_ID',25)->nullable(); // -- 15
            $table->string('CRT_DATE_DELETED',19)->nullable(); // -- 16
            $table->datetime('CONTRACT_START')->nullable(); // -- 17
            $table->datetime('CONTRACT_END')->nullable(); // -- 18
            $table->datetime('ROLE_DT')->nullable(); // -- 19
            $table->integer('NOID')->primary(); // -- 20

            // ---------------------
            $table->timestamps();
            $table->softDeletes();
        });

        DB::statement('ALTER TABLE com_user_profiles ALTER COLUMN ID VARCHAR(20)');
        DB::statement('ALTER TABLE com_user_profiles ALTER COLUMN PWD VARCHAR(100)');
        DB::statement('ALTER TABLE com_user_profiles ALTER COLUMN NICK_NAME VARCHAR(100)');
        DB::statement('ALTER TABLE com_user_profiles ALTER COLUMN PWD_CREATE_DT VARCHAR(19)');
        DB::statement('ALTER TABLE com_user_profiles ALTER COLUMN INSTITUTION_ID VARCHAR(10)');
        DB::statement('ALTER TABLE com_user_profiles ALTER COLUMN ACL_ID VARCHAR(3)');
        DB::statement('ALTER TABLE com_user_profiles ALTER COLUMN LOGIN_DT VARCHAR(19)');
        DB::statement('ALTER TABLE com_user_profiles ALTER COLUMN LOGIN_STS_ID VARCHAR(1)');
        DB::statement('ALTER TABLE com_user_profiles ALTER COLUMN STS_ID VARCHAR(3)');
        DB::statement('ALTER TABLE com_user_profiles ALTER COLUMN UPD_ID VARCHAR(20)');
        DB::statement('ALTER TABLE com_user_profiles ALTER COLUMN UPD_DT VARCHAR(19)');
        DB::statement('ALTER TABLE com_user_profiles ALTER COLUMN EMAIL_ID VARCHAR(50)');
        DB::statement('ALTER TABLE com_user_profiles ALTER COLUMN PHONE_NO_ID VARCHAR(25)');
        DB::statement('ALTER TABLE com_user_profiles ALTER COLUMN HP_ID VARCHAR(25)');
        DB::statement('ALTER TABLE com_user_profiles ALTER COLUMN CRT_DATE_DELETED VARCHAR(19)');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('com_user_profiles');
    }
};
