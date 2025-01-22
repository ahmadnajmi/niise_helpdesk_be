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
            CREATE FUNCTION dbo.getCategory
            (
                @category VARCHAR(MAX),
                @delimeter CHAR,
                @level INT
            )
            RETURNS VARCHAR(100)
            AS
            BEGIN
                -- Declare the return variable here
                DECLARE @position INT = 0
                DECLARE @len INT = 0
                DECLARE @rtnCat VARCHAR(100)
                DECLARE @levelInd INT = 1
                DECLARE @value VARCHAR(100)
                DECLARE @rtnValue VARCHAR(100) = NULL

                -- Set values
                SET @position = 0
                SET @len = 0
                SET @category = @category + @delimeter

                -- Function
                WHILE @levelInd <= @level
                BEGIN
                    SET @len = CHARINDEX(@delimeter, @category, @position+1) - @position
                    SET @value = SUBSTRING(@category, @position, @len)

                    SET @position = CHARINDEX(@delimeter, @category, @position+@len) +1
                    SET @levelInd = @levelInd + 1

                    SET @rtnValue =  concat_ws(@delimeter,@rtnValue,@value)
                END

                -- Return the result of the function
                RETURN @rtnValue
            END
        ");

        // DB::statement("
        //     CREATE FUNCTION dbo.getCategory
        //     (
        //         @category VARCHAR(MAX),
        //         @delimeter CHAR,
        //         @level INT
        //     )
        //     RETURNS VARCHAR(100)
        //     AS
        //     BEGIN
        //         -- Declare the return variable here
        //         DECLARE @position INT = 1;   -- Starting position
        //         DECLARE @len INT = 0;        -- Length of the substring
        //         DECLARE @levelInd INT = 1;   -- Current level
        //         DECLARE @value VARCHAR(100); -- Value extracted at the current level
        //         DECLARE @rtnValue VARCHAR(100) = ''; -- Final return value

        //         -- Append delimiter to ensure parsing ends correctly
        //         SET @category = @category + @delimiter;

        //         -- Loop through levels to extract up to the desired level
        //         WHILE @levelInd <= @level
        //         BEGIN
        //             -- Find the position of the next delimiter
        //             SET @len = CHARINDEX(@delimiter, @category, @position) - @position;

        //             -- Exit loop if no more delimiters are found
        //             IF @len < 0 BREAK;

        //             -- Extract the value at the current level
        //             SET @value = LTRIM(RTRIM(SUBSTRING(@category, @position, @len)));

        //             -- Advance to the next position after the current delimiter
        //             SET @position = CHARINDEX(@delimiter, @category, @position) + 1;

        //             -- Append the value to the return variable if this is the target level
        //             IF @levelInd = @level
        //                 SET @rtnValue = @value;

        //             -- Increment the level indicator
        //             // SET @levelInd = @levelInd + 1;
        //         END

        //         -- Return the result of the function
        //         RETURN @rtnValue
        //     END
        // ");

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("DROP FUNCTION IF EXISTS dbo.getCategory");
    }
};
