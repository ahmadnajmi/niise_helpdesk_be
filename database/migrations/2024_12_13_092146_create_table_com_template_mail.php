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
        Schema::create('comtemplatemail', function (Blueprint $table) {

            $table->bigIncrements('ID')->primary(); // -- 1
            $table->string('name', 50); // -- 2
            $table->string('sender',50); // -- 3
            $table->string('sender_name',50)->nullable(); // -- 4
            $table->string('template',25); // -- 5
            $table->text('intro')->nullable(); // -- 6
            $table->text('footer')->nullable(); // -- 7
            $table->string('created_by',25); // -- 8
            $table->datetime('created_at'); // -- 9
            $table->string('updated_by',25)->nullable(); // -- 10
            $table->datetime('updated_at')->nullable(); // -- 11
            // ---------------------------
            // $table->timestamps(); // already included
            $table->softDeletes();
        });

        DB::statement('ALTER TABLE comtemplatemail ALTER COLUMN name VARCHAR(50)');
        DB::statement('ALTER TABLE comtemplatemail ALTER COLUMN sender VARCHAR(50)');
        DB::statement('ALTER TABLE comtemplatemail ALTER COLUMN sender_name VARCHAR(50)');
        DB::statement('ALTER TABLE comtemplatemail ALTER COLUMN template VARCHAR(25)');
        DB::statement('ALTER TABLE comtemplatemail ALTER COLUMN created_by VARCHAR(25)');
        DB::statement('ALTER TABLE comtemplatemail ALTER COLUMN updated_by VARCHAR(25)');


    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comtemplatemail');
    }
};
