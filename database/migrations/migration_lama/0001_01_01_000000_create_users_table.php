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
        // Schema::create('users', function (Blueprint $table) {
        //     $table->id();
        //     $table->string('name');
        //     $table->string('email')->unique();
        //     $table->timestamp('email_verified_at')->nullable();
        //     $table->string('password');
        //     $table->rememberToken();
        //     $table->timestamps();
        // });

        Schema::create('users', function (Blueprint $table) {

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

        DB::statement('ALTER TABLE users ALTER COLUMN ID VARCHAR(20)');
        DB::statement('ALTER TABLE users ALTER COLUMN PWD VARCHAR(100)');
        DB::statement('ALTER TABLE users ALTER COLUMN NICK_NAME VARCHAR(100)');
        DB::statement('ALTER TABLE users ALTER COLUMN PWD_CREATE_DT VARCHAR(19)');
        DB::statement('ALTER TABLE users ALTER COLUMN INSTITUTION_ID VARCHAR(10)');
        DB::statement('ALTER TABLE users ALTER COLUMN ACL_ID VARCHAR(3)');
        DB::statement('ALTER TABLE users ALTER COLUMN LOGIN_DT VARCHAR(19)');
        DB::statement('ALTER TABLE users ALTER COLUMN LOGIN_STS_ID VARCHAR(1)');
        DB::statement('ALTER TABLE users ALTER COLUMN STS_ID VARCHAR(3)');
        DB::statement('ALTER TABLE users ALTER COLUMN UPD_ID VARCHAR(20)');
        DB::statement('ALTER TABLE users ALTER COLUMN UPD_DT VARCHAR(19)');
        DB::statement('ALTER TABLE users ALTER COLUMN EMAIL_ID VARCHAR(50)');
        DB::statement('ALTER TABLE users ALTER COLUMN PHONE_NO_ID VARCHAR(25)');
        DB::statement('ALTER TABLE users ALTER COLUMN HP_ID VARCHAR(25)');
        DB::statement('ALTER TABLE users ALTER COLUMN CRT_DATE_DELETED VARCHAR(19)');

        // ----------------------------------------------------------------------

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
