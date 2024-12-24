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
            CREATE FUNCTION dbo.getCategory2
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
                DECLARE @Lastlen INT = 0
                DECLARE @rtnCat VARCHAR(100)
                DECLARE @levelInd INT = 0
                DECLARE @value VARCHAR(100)
                DECLARE @rtnValue VARCHAR(100) = NULL
                DECLARE @LENRTNVAL INT = 0

                -- Set values
                SET @position = 0
                SET @len = 0
                SET @category = @category + @delimeter

                -- Function
                WHILE @levelInd < @level
                BEGIN
                    SET @len = CHARINDEX(@delimeter, @category, @position+1) - @position

                    IF(@len<=0)
                        BEGIN
                            IF(@LENRTNVAL != 0)
                                BEGIN
                                    --SET @Lastlen = len(@category)-@position
                                    IF(len(@category)-@position < 0)
                                        return @rtnValue
                                    ELSE

                                    SET @value = SUBSTRING(@category, @position, len(@category)-@position)

                                    --SET @position = CHARINDEX(@delimeter, @category, @position+@len) +1
                                    --SET @rtnValue =  concat_ws(@delimeter,@rtnValue,@value)
                                    SET @rtnValue = @rtnValue  +  @value
                                END
                            ELSE

                            SET @rtnValue = @category
                        END
                    ELSE

                    BEGIN
                        set @value = SUBSTRING(@category, @position, @len)
                        set @position = CHARINDEX(@delimeter, @category, @position+@len) +1
                        set @rtnValue =  concat_ws(@delimeter,@rtnValue,@value)
                    END

                    SET @levelInd = @levelInd + 1
                    SET @LENRTNVAL = len(@rtnValue)
                END

                -- Return the result of the function
                --RETURN CAST(@rtnValue as varchar(10)) + cast(@levelInd as varchar(2)) + cast(@LENRTNVAL as varchar(2)) + cast(@position as varchar(2))
                RETURN @rtnValue
            END
        ");

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("DROP FUNCTION IF EXISTS dbo.getCategory2");
    }
};
