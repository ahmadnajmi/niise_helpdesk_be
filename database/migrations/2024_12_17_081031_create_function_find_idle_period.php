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
            CREATE FUNCTION dbo.find_idle_period
            (
                @log_no VARCHAR(20)
            )
            RETURNS VARCHAR(30)
            AS
            BEGIN
                -- Declare the return variable here
                DECLARE @latestReso DATETIME;
                DECLARE @return VARCHAR(30);

                -- Add the T-SQL statements to compute the return value here
                SELECT @latestReso = max(cr_reso_datetm)
                FROM dbo.hd_case_resolution
                WHERE cr_log_no = @log_no;

                -- Calculate the idle period (days difference)
                IF @latestReso IS NOT NULL
                    SELECT @return = CAST(DATEDIFF(DAY, @latestReso, GETDATE()) AS VARCHAR(30));
                ELSE
                    SELECT @return = NULL;

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
        DB::statement("DROP FUNCTION IF EXISTS dbo.find_idle_period");
    }
};
