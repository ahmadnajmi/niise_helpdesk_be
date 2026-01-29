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
            $table->uuid('id')->unique()->primary();
            $table->string('incident_no',20)->nullable()->index(); 
            $table->string('code_sla',20)->nullable(); 
            $table->date('incident_date')->nullable();
            $table->string('barcode',100)->nullable(); 
            $table->unsignedBigInteger('branch_id')->nullable(); 
            $table->unsignedBigInteger('category_id')->nullable(); 
            $table->uuid('complaint_user_id')->nullable()->index(); 
            $table->mediumText('information')->nullable(); 
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
            $table->uuid('service_recipient_id')->nullable(); 
            $table->unsignedBigInteger('group_id')->nullable(); 
            $table->uuid('operation_user_id')->nullable()->index(); 
            $table->dateTime('expected_end_date')->nullable();
            $table->dateTime('actual_end_date')->nullable();
            $table->smallInteger('status')->default(1);
            $table->uuid('resolved_user_id')->nullable()->index(); 
            $table->unsignedBigInteger('assign_group_id')->nullable(); 
            $table->unsignedBigInteger('assign_company_id')->nullable(); 
            $table->unsignedBigInteger('sla_version_id')->nullable(); 
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
