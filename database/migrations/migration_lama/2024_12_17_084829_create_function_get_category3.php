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
            CREATE FUNCTION dbo.getCategory3
            (
                @category VARCHAR(MAX),
                @delimeter CHAR,
                @level INT
            )
            RETURNS VARCHAR(100)
            WITH SCHEMABINDING
            AS
            BEGIN
                -- Declare the return variable here
                DECLARE @position INT = 0
                DECLARE @len INT = 0
                DECLARE @levelInd INT = 0
                DECLARE @value VARCHAR(100)

                -- Set values
                SET @position = 0
                SET @len = 0
                --SET @category = @category + @delimeter

                -- Function
                WHILE @levelInd < @level
                BEGIN
                    SET @len += CHARINDEX(@delimeter, @category, @position)

                    IF (@len = 0)
                        SET @value = @category

                    ELSE
                        SET @value = SUBSTRING(@category,0,@len)

                    SET @levelInd = @levelInd + 1

                    IF (@len = 0)
                        break;
                END


                -- Return the result of the function
                --RETURN CAST(@rtnValue as varchar(10)) + cast(@levelInd as varchar(2)) + cast(@LENRTNVAL as varchar(2)) + cast(@position as varchar(2))
	            RETURN @value
            END
        ");

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("DROP FUNCTION IF EXISTS dbo.getCategory3");
    }
};
