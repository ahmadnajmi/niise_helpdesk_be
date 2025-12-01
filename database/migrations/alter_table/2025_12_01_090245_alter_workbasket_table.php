<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('workbasket', function (Blueprint $table) {
            $table->boolean('escalate_frontliner')->default(false);

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('workbasket', function (Blueprint $table) {
            $table->dropColumn('escalate_frontliner');
        });
    }
};
