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
       Schema::table('incident_resolution', function (Blueprint $table) {
            $table->text('notes_clob')->nullable();
            $table->text('solution_notes_clob')->nullable();
        });

        DB::statement('
            UPDATE incident_resolution
            SET notes_clob = notes,
                solution_notes_clob = solution_notes
        ');

        Schema::table('incident_resolution', function (Blueprint $table) {
            $table->dropColumn(['notes', 'solution_notes']);
        });

        Schema::table('incident_resolution', function (Blueprint $table) {
            $table->renameColumn('notes_clob', 'notes');
            $table->renameColumn('solution_notes_clob', 'solution_notes');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
