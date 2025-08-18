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
        Schema::create('incidents', function (Blueprint $table) {
            $table->id();
            $table->string('incident_no',20)->nullable(); 
            $table->string('code_sla',20)->nullable(); 
            $table->date('incident_date')->nullable();
            $table->string('barcode',100)->nullable(); 
            $table->unsignedBigInteger('branch_id')->nullable(); 
            $table->unsignedBigInteger('category_id')->nullable(); 
            $table->unsignedBigInteger('complaint_id')->nullable(); 
            $table->string('information',255)->nullable(); 
            $table->unsignedBigInteger('knowledge_base_id')->nullable(); 
            $table->smallInteger('received_via')->nullable();
            $table->string('report_no',100)->nullable(); 
            $table->smallInteger('incident_asset_type')->nullable();
            $table->date('date_asset_loss')->nullable();
            $table->date('date_report_police')->nullable();
            $table->string('report_police_no',100)->nullable(); 
            $table->string('asset_siri_no',100)->nullable(); 
            $table->unsignedBigInteger('asset_parent_id')->nullable(); 
            $table->json('asset_component_id')->nullable(); 
            $table->unsignedBigInteger('group_id')->nullable(); 
            $table->unsignedBigInteger('operation_user_id')->nullable(); 
            $table->string('appendix_file',100)->nullable(); 
            $table->string('asset_file',100)->nullable(); 
            $table->dateTime('end_date')->nullable();
            $table->smallInteger('status')->default(1);

            $table->log();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('incidents');
    }
};
