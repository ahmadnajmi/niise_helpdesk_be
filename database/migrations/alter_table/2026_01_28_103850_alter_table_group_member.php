<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('user_groups', function (Blueprint $table) {
            $table->smallInteger('user_type')->nullable();
            $table->string('ic_no',12)->nullable()->index(); 
            $table->string('name',100)->nullable();
            $table->string('email',100)->nullable();
            $table->unsignedBigInteger('company_id')->nullable();
        });

        DB::statement('
            ALTER TABLE user_groups
            MODIFY (user_id NULL)
        ');
    }

    public function down(): void
    {
        Schema::table('user_groups', function (Blueprint $table) {
            $table->dropColumn('ic_no');
            $table->dropColumn('user_type');
            $table->dropColumn('name');
            $table->dropColumn('email');
            $table->dropColumn('company_id');
        });
    }

};
