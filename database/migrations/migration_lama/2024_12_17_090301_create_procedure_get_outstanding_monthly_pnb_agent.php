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
        CREATE PROCEDURE dbo.GetOutstandingMonthlyPNBAgent
        AS
        BEGIN
            -- SET NOCOUNT ON added to prevent extra result sets from
	        -- interfering with SELECT statements.
            SET NOCOUNT ON;

            DECLARE @List varchar(8000)

            DECLARE
            @incidentNo varchar(50),
            @companyCode varchar (150),
            @State varchar(16),
            @caller varchar(105),
            @email varchar(100),
            @createdate varchar(19),
            @duedate varchar(19),
            @closeddate varchar(19),
            @logno varchar(15),
            @severity varchar(4),
            @category varchar(254),
            @description varchar(1000),
            @aging varchar(6),
            @parent varchar(255)


            DECLARE
            @resocreatedate varchar(19),
            @resoenddate varchar(100),
            @action varchar(10),
            @resolutiondesc varchar(1000)

            CREATE TABLE #Temp1
            (
                incidentNo varChar( 50 ),
                companyCode varchar (150),
                State varchar(16),
                caller varchar(105),
                email varchar(100),
                createdate varchar(19),
                duedate varchar(19),
                closeddate varchar(19),
                logno varchar(15),
                severity varchar(4),
                category varchar(254),
                description varchar(1000),
                aging varchar(6),
                parent varchar(255),
                resolution varchar(8000)
            )


            SET @List = ''

            DECLARE CaseMasterCursor CURSOR FOR
            SELECT
                isnull(a.cm_customer_rptno, '-') AS incidentNo,
                isnull(c.cu_customer_Shortname + ' ' + d.cb_branch_Name,'-') AS companyCode,
                --'serial number' as seialno, 'model number' as modelNo, 'model description' as modelDesc
                (case a.cm_status when '6' then 'CLOSED' when '7' then 'CANCEL/DUPLICATE' when '4' then 'OPEN' when '5' then 'RESOLVED' end) AS State,
                isnull(h.ci_name + ' / ' + h.ci_caller_no, '-') as caller,
                isnull(h.ci_email, '-') as email,
                ISNULL(CONVERT(varchar, a.cm_create_date,103) + ' ' + CONVERT(varchar, a.cm_create_date,108) , '-')  as createdate,
                ISNULL(CONVERT(varchar, a.cm_due_datetm,103) + ' ' + CONVERT(varchar, a.cm_due_datetm,108) , '-')  as duedate,
                ISNULL(CONVERT(varchar, a.cm_close_datetm,103) + ' ' + CONVERT(varchar, a.cm_close_datetm,108) , '-')  as closeddate,
                isnull(a.cm_log_no, '-') as logno,
                isnull(a.cm_severity,'-') as severity,
                isnull(e.Ct_Description, '-') as category,
                (Replace(isnull(a.cm_description, '-') ,CHAR(13)+CHAR(10)+CHAR(13)+CHAR(10),CHAR(13)+CHAR(10)) + CHAR(10))as description,
                --isnull(a.cm_description, '-') as description,
                DATEDIFF(d,a.cm_create_date, GETDATE()) AS aging,
                isnull((select ct_description from refCategory where ct_code=e.ct_parent),e.Ct_Description) as parent

            FROM
                HD_Case_Master a
                INNER JOIN HD_Customer_Master c ON a.cm_customer_id = c.cu_customer_ID
                INNER JOIN HD_Customer_Branch d ON a.cm_branch_code = d.cb_branchcode AND a.cm_customer_id = d.cb_customer_ID
                INNER JOIN refCategory e ON a.cm_category = e.Ct_Code
                INNER JOIN refCase_Status f ON a.cm_status = f.cs_case_sts_code
                LEFT JOIN hd_caller_info h ON a.cm_caller_id=h.ci_caller_id

            WHERE
                a.cm_customer_id='081' --pnb
                AND d.cb_branchcode in ('0055','0060','0061','0064','0065','0067','0115','0117','0126','0149','0160')
                --and a.cm_category like '01'+'%' -- hardware
                --and (a.cm_category like '14-01%' or a.cm_category like '14-03%') -- pnb
                AND a.cm_rectype ='I'
                AND ( a.cm_status ='4' or a.cm_status ='5' or a.cm_status='6')
                --and a.cm_create_date BETWEEN '2017-03-14 00:00:00.000' AND '2017-03-14 23:59:00.000'
                --and DATEADD(dd, 0, DATEDIFF(dd, 0, a.cm_create_date)) = DATEADD(dd, 0, DATEDIFF(dd, 0, GETDATE()-1))
                AND a.cm_create_date  between dateadd(mm, datediff(mm, 0, dateadd(MM, -1, getdate())), 0)
                AND dateadd(ms, -3, dateadd(mm, datediff(mm, 0, dateadd(MM, -1, getdate())) + 1, 0))

            ORDER BY
                a.cm_customer_id,
                a.cm_branch_code,
                a.cm_log_no



            OPEN CaseMasterCursor

                FETCH NEXT FROM CaseMasterCursor INTO @incidentNo, @companyCode, @State, @caller, @email, @createdate, @duedate, @closeddate, @logno, @severity, @category, @description,@aging, @parent
                WHILE @@FETCH_STATUS = 0

                BEGIN

                    --PRINT @LogNo

                    DECLARE CaseResolutionCursor CURSOR FOR
                    select
                    ISNULL(CONVERT(varchar, b.cr_reso_datetm,103) + ' ' + CONVERT(varchar, b.cr_reso_datetm,108) , '-')  as resocreatedate
                    --,ISNULL(CONVERT(varchar, b.cr_end_date,103) + ' ' + CONVERT(varchar, b.cr_end_date,108) , '-')  as resoenddate
                    --,ISNULL(CONVERT(varchar, b.cr_end_date,103) + ' ' + CONVERT(varchar, b.cr_end_date,108) ,space(30))  as resoenddate
                    ,(case (isdate(b.cr_end_date)) when 1 then (CONVERT(varchar, b.cr_end_date,103) + space(1) + CONVERT(varchar, b.cr_end_date,108)) else space(33) end)  as resoenddate
                    ,ISNULL(g.ac_abbreviation, '-') AS action
                    ,replace(ISNULL(b.cr_resolution, '-'), CHAR(13)+CHAR(10), '') as resolutiondesc

                    from hd_case_resolution b INNER JOIN
                    refAction g ON b.cr_action = g.ac_code
                    where b.cr_log_no=@logno and g.ac_abbreviation <> 'INTR'
                    order by b.cr_reso_datetm





                    OPEN CaseResolutionCursor

                    FETCH NEXT FROM CaseResolutionCursor INTO @resocreatedate, @resoenddate, @action, @resolutiondesc
                    WHILE @@FETCH_STATUS = 0
                    BEGIN
                        --PRINT '+' + resolutiondesc + '+'

                        IF @List = ''
                            BEGIN
                                --SET @List = @resocreatedate + char(9) + char(9) + @resoenddate + char(9) + char(9) + @action + char(9) + char(9) + @resolutiondesc
                                --SET @List = @resocreatedate + '       ' + @resoenddate + '      [' + rtrim(@action) + ']  ' +  @resolutiondesc
                                SET @List = @resocreatedate + char(32) + char(32)+ char(32) + char(32)+ char(32) + char(32)+ char(32) + char(32) + char(32)
                                + @resoenddate +char(32) + char(32)+ char(32) + char(32)+ char(32) + char(32)+ char(32) + char(32) + '[' + rtrim(@action) + ']'+ char(32)+  @resolutiondesc + char(13) + char(10)
                            END
                        ELSE
                            BEGIN
                                --SET @List = @List + CHAR(13)+ CHAR(10) + @resocreatedate + char(9) + char(9) +  @resoenddate + char(9) + char(9) + @action + char(9) + char(9) + @resolutiondesc
                                --SET @List = @List + CHAR(13)+ CHAR(10) + @resocreatedate + '       ' +  @resoenddate + '      [' + rtrim(@action) + ']  ' +  @resolutiondesc
                                SET @List = @List + CHAR(13) + CHAR(10) + @resocreatedate + char(32) + char(32)+ char(32) + char(32)+ char(32) + char(32)+ char(32) + char(32) + char(32)
                                + @resoenddate + char(32) + char(32)+ char(32) + char(32)+ char(32) + char(32)+ char(32) + char(32) + '[' + rtrim(@action) + ']' + char(32) +  @resolutiondesc + char(13) + char(10)

                        END

                        FETCH NEXT FROM CaseResolutionCursor INTO @resocreatedate, @resoenddate, @action, @resolutiondesc
                    END

                    CLOSE CaseResolutionCursor
                    DEALLOCATE CaseResolutionCursor

                    insert into #Temp1 values(@incidentNo, @companyCode, @State, @caller, @email, @createdate, @duedate, @closeddate, @logno, @severity, @category, @description,@aging, @parent, @List)

                    SET @List = ''

                    FETCH NEXT FROM CaseMasterCursor INTO @incidentNo, @companyCode, @State, @caller, @email, @createdate, @duedate, @closeddate, @logno, @severity, @category, @description,@aging, @parent
                END

                CLOSE CaseMasterCursor
                DEALLOCATE CaseMasterCursor

                SELECT * FROM #Temp1
                --delete #Temp1
        END
    ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("DROP PROCEDURE IF EXISTS dbo.GetOutstandingMonthlyPNBAgent");
    }
};
