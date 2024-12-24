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
            CREATE TABLE slmBreachedReport (
                [logNo] VARCHAR(1) NOT NULL,
                [custReportNo] VARCHAR(1) NOT NULL,
                [customerID] VARCHAR(1) NOT NULL,
                [shortName] VARCHAR(1) NOT NULL,
                [longName] VARCHAR(1) NOT NULL,
                [branchCode] VARCHAR(1) NOT NULL,
                [branchName] VARCHAR(1) NOT NULL,
                [callerName] VARCHAR(1) NOT NULL,
                [callerPhone] VARCHAR(1) NOT NULL,
                [cmDateCreate] VARCHAR(1) NOT NULL,
                [dateReport] VARCHAR(1) NOT NULL,
                [timeReport] VARCHAR(1) NOT NULL,
                [dateClose] VARCHAR(1) NOT NULL,
                [timeClose] VARCHAR(1) NOT NULL,
                [severity] VARCHAR(1) NOT NULL,
                [prCode] VARCHAR(1) NOT NULL,
                [prCategory] VARCHAR(1) NOT NULL,
                [category] VARCHAR(1) NOT NULL,
                [cmDescription] VARCHAR(1) NOT NULL,
                [dateCreate] VARCHAR(1) NOT NULL,
                [timeCreate] VARCHAR(1) NOT NULL,
                [dateEnd] VARCHAR(1) NOT NULL,
                [timeEnd] VARCHAR(1) NOT NULL,
                [createdBy] VARCHAR(1) NOT NULL,
                [staffIncharge] VARCHAR(1) NOT NULL,
                [groupIncharge] VARCHAR(1) NOT NULL,
                [vendor] VARCHAR(1) NOT NULL,
                [action] VARCHAR(1) NOT NULL,
                [statusCode] VARCHAR(1) NOT NULL,
                [resolutionDesc] VARCHAR(1) NOT NULL,
                [dueDate] VARCHAR(1) NOT NULL,
                [dueTime] VARCHAR(1) NOT NULL,
                [getToday] VARCHAR(1) NOT NULL,
                [getDateBreach] VARCHAR(1) NOT NULL,
                [getDate] VARCHAR(1) NOT NULL,
                [getWeek] VARCHAR(1) NOT NULL,
                [getMonth] VARCHAR(1) NOT NULL,
                [statusDesc] VARCHAR(1) NOT NULL,
                [aging] INT NOT NULL,
                [aging_hour] INT NOT NULL,
                [aging_minute] INT NOT NULL,
                [breachPeriod] VARCHAR(1) NOT NULL,
                [tbpsDisclaimer] INT NOT NULL,
                [sl_sla_code] VARCHAR(1) NULL,
                [cm_sla_code] VARCHAR(1) NULL,
                [problemTimeFrame] INT NULL,
                [recType] VARCHAR(1) NULL,
                [cmCreateDate] DATETIME NULL,
                [crEndDateTime] DATETIME NULL
            );

            -- Removing temporary table and create final VIEW structure
            DROP TABLE IF EXISTS slmBreachedReport;


        ");

        DB::statement("
            CREATE VIEW dbo.slmBreachedReport
            AS
            SELECT
                dbo.HD_Case_Master.cm_log_no AS logNo,
                ISNULL(dbo.HD_Case_Master.cm_customer_rptno, '-') AS custReportNo,
                ISNULL(dbo.HD_Case_Master.cm_customer_id, '-') AS customerID, ISNULL(dbo.HD_Customer_Master.cu_customer_Shortname, '-') AS shortName,
                ISNULL(dbo.HD_Customer_Master.cu_customer_Name, '-') AS longName, ISNULL(dbo.HD_Case_Master.cm_branch_code, '-') AS branchCode,
                ISNULL(dbo.HD_Customer_Branch.cb_branch_Name, '-') AS branchName, ISNULL(dbo.HD_Caller_Info.ci_name, '-') AS callerName,
                ISNULL(dbo.HD_Caller_Info.ci_caller_no, '-') AS callerPhone, ISNULL(CONVERT(varchar, dbo.HD_Case_Master.cm_create_date, 101), '-')
                AS cmDateCreate, ISNULL(CONVERT(varchar, dbo.HD_Case_Master.cm_start_date, 101), '-') AS dateReport, ISNULL(CONVERT(varchar,
                dbo.HD_Case_Master.cm_start_date, 108), '-') AS timeReport, ISNULL(CONVERT(varchar, dbo.HD_Case_Master.cm_close_datetm, 101), '-')
                AS dateClose, ISNULL(CONVERT(varchar, dbo.HD_Case_Master.cm_close_datetm, 108), '-') AS timeClose, ISNULL(dbo.HD_Case_Master.cm_severity,
                '-') AS severity, ISNULL(SUBSTRING(dbo.HD_Case_Master.cm_category, 1, 2), '-') AS prCode, ISNULL(dbo.refMainCategory.Ct_Abbreviation, '-')
                AS prCategory, ISNULL(dbo.refCategory.Ct_Abbreviation, '-') AS category, ISNULL(dbo.HD_Case_Master.cm_description, '-') AS cmDescription,
                ISNULL(CONVERT(varchar, dbo.HD_Case_Resolution.cr_reso_datetm, 101), '-') AS dateCreate, ISNULL(CONVERT(varchar,
                dbo.HD_Case_Resolution.cr_reso_datetm, 108), '-') AS timeCreate, ISNULL(CONVERT(varchar, dbo.HD_Case_Resolution.cr_end_date, 101), '-')
                AS dateEnd, ISNULL(CONVERT(varchar, dbo.HD_Case_Resolution.cr_end_date, 108), '-') AS timeEnd, ISNULL(dbo.HD_Case_Resolution.cr_create_id,
                '-') AS createdBy, ISNULL(dbo.HD_Person_Info.pi_name, '-') AS staffIncharge, ISNULL(dbo.HD_Contact_Group.cg_group_id, '-') AS groupIncharge,
                ISNULL(dbo.HD_Case_Resolution.cr_vendor_id, '-') AS vendor, ISNULL(dbo.refAction.ac_abbreviation, '-') AS action,
                ISNULL(dbo.HD_Case_Master.cm_status, '-') AS statusCode, ISNULL(dbo.HD_Case_Resolution.cr_resolution, '-') AS resolutionDesc,
                ISNULL(CONVERT(varchar, dbo.HD_Case_Master.cm_due_datetm, 101), '-') AS dueDate, ISNULL(CONVERT(varchar,
                dbo.HD_Case_Master.cm_due_datetm, 108), '-') AS dueTime, ISNULL(CONVERT(varchar, GETDATE(), 101), '-') AS getToday, ISNULL(CONVERT(varchar,
                GETDATE() + 2, 101), '-') AS getDateBreach, ISNULL(CONVERT(varchar, GETDATE() - 1, 101), '-') AS getDate, ISNULL(CONVERT(varchar,
                DATEADD(WEEK, - 1, GETDATE()), 101), '-') AS getWeek, ISNULL(CONVERT(varchar, DATEADD(MONTH, - 1, GETDATE()), 101), '-') AS getMonth,
                ISNULL(dbo.refCase_Status.cs_case_sts_desc, '-') AS statusDesc, ISNULL(DATEDIFF(dd, dbo.HD_Case_Master.cm_create_date, GETDATE()), '-')
                AS aging, ISNULL(DATEDIFF(hh, dbo.HD_Case_Master.cm_create_date, GETDATE()), '-') AS aging_hour, ISNULL(DATEDIFF(mi,
                dbo.HD_Case_Master.cm_create_date, GETDATE()), '-') AS aging_minute, 'dbo.HD_Case_Master.cm_due_datetm > GETDATE()' AS breachPeriod,
                ISNULL(CONVERT(int, dbo.HD_Case_Master.cm_vendor_id), '-') AS tbpsDisclaimer, dbo.HD_SLA.sl_sla_code, dbo.HD_Case_Master.cm_sla_code,
                dbo.HD_SLA.sl_due_date_timeframe AS problemTimeFrame, dbo.HD_Case_Master.cm_rectype AS recType,
                dbo.HD_Case_Master.cm_create_date AS cmCreateDate, dbo.HD_Case_Resolution.cr_end_date AS crEndDateTime

                FROM
                    dbo.HD_Case_Master INNER JOIN
                    dbo.HD_Case_Resolution ON dbo.HD_Case_Master.cm_log_no = dbo.HD_Case_Resolution.cr_log_no LEFT OUTER JOIN
                    dbo.HD_Customer_Master ON dbo.HD_Case_Master.cm_customer_id = dbo.HD_Customer_Master.cu_customer_ID LEFT OUTER JOIN
                    dbo.HD_Customer_Branch ON dbo.HD_Case_Master.cm_branch_code = dbo.HD_Customer_Branch.cb_branchcode AND
                    dbo.HD_Case_Master.cm_customer_id = dbo.HD_Customer_Branch.cb_customer_ID LEFT OUTER JOIN
                    dbo.refCategory ON dbo.HD_Case_Master.cm_category = dbo.refCategory.Ct_Code LEFT OUTER JOIN
                    dbo.refCase_Status ON dbo.HD_Case_Master.cm_status = dbo.refCase_Status.cs_case_sts_code LEFT OUTER JOIN
                    dbo.refAction ON dbo.HD_Case_Resolution.cr_action = dbo.refAction.ac_code LEFT OUTER JOIN
                    dbo.HD_Person_Info ON dbo.HD_Case_Resolution.cr_fwd_to = dbo.HD_Person_Info.pi_person_id LEFT OUTER JOIN
                    dbo.HD_Contact_Group ON dbo.HD_Person_Info.pi_person_id = dbo.HD_Contact_Group.cg_personal_id LEFT OUTER JOIN
                    dbo.refMainCategory ON SUBSTRING(dbo.HD_Case_Master.cm_category, 1, 2) = dbo.refMainCategory.Ct_Code LEFT OUTER JOIN
                    dbo.HD_SLA ON dbo.HD_Case_Master.cm_sla_code = dbo.HD_SLA.sl_sla_code LEFT OUTER JOIN
                    dbo.HD_Caller_Info  ON cm_caller_id = HD_Caller_Info.ci_caller_id
                ;

        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("DROP VIEW IF EXISTS dbo.slmBreachedReport");
    }
};
