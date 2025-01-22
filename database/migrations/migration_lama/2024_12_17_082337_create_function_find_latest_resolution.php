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
        // Scalar Function
        DB::statement("
            CREATE FUNCTION dbo.find_latest_resolution
            (
                @log_no VARCHAR(20)
            )
            RETURNS DATETIME
            AS
            BEGIN
                -- Declare the return variable here
                DECLARE @return DATETIME;

                -- Add the T-SQL statements to compute the return value here
                SELECT @return = max(cr_reso_datetm)
                FROM dbo.hd_case_resolution
                WHERE cr_log_no = @log_no

                -- Return the result of the function
                RETURN @return
            END
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("DROP FUNCTION IF EXISTS dbo.find_latest_resolution");
    }
};
