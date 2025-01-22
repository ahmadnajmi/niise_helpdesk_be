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
        DB::statement("
            CREATE FUNCTION dbo.TotalVClosed2
            (
                @customer_id1 VARCHAR(10),
                @customer_id2 VARCHAR(10)
            )
            RETURNS INT
            AS
            BEGIN
                -- Declare the return variable here
                DECLARE @c INT;

                -- Set values
                SET @c = (
                    SELECT COUNT(a.cm_log_no)
                    FROM HD_Case_Master AS a
                    INNER JOIN HD_Customer_Master AS c
                        ON a.cm_customer_id = c.cu_customer_ID
                    INNER JOIN refCategory AS b
                        ON dbo.getCategory3(a.cm_category, '-', 1) = b.ct_code
                    WHERE a.cm_customer_id <> @customer_id1
                        AND a.cm_customer_id <> @customer_id2
                        AND a.cm_rectype = 'I'
                        AND a.cm_status = '6'
                        AND a.cm_close_datetm BETWEEN
                            DATEADD(MONTH, -1, GETDATE()) AND
                            DATEADD(DAY, 0, DATEDIFF(DAY, 0, GETDATE()) )
                );

                -- Return the result of the function
                RETURN @c;
            END
        ");

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("DROP FUNCTION IF EXISTS dbo.TotalVClosed2");
    }
};
