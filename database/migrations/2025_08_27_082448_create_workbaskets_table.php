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
        Schema::create('workbasket', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('incident_id')->nullable(); 
            $table->date('date');
            $table->unsignedBigInteger('handle_by')->nullable(); 
            $table->smallInteger('status')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('workbasket');
    }
};
