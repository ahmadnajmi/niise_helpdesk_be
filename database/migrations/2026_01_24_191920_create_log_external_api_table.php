<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('log_external_api', function (Blueprint $table) {
            $table->id();
            $table->string('service_name', 100);
            $table->string('endpoint', 255);
            $table->boolean('is_success')->index();
            $table->unsignedSmallInteger('status_code');
            $table->text('request')->nullable();
            $table->text('response')->nullable();
            $table->string('error_message')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void{
        Schema::dropIfExists('log_external_api');
    }
};
