<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ActionCodeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::statement("
            INSERT INTO refAction (ac_code, ac_abbreviation, ac_description1, ac_description2, ac_category, ac_create_id, ac_create_date, ac_update_id, ac_update_date, ac_status_rec, ac_value, ac_type, ac_email_status, ac_email_recipients)
            VALUES
            ('01', 'ACTR', 'ACTUAL RESOLUTION', 'SYSTEM UP AND OPERATIONAL AS USUAL', 'DISC', 'asmidah', '2009-08-14 10:15:52.000', 'Faryna', '2020-12-01 10:15:52.163', 'R01', NULL, 'C', 'NO', NULL),
            ('02', 'CLSD', 'CLOSE', 'CLOSE INCIDENT LOG AFTER VERIFIED BY THE CUSTOMER', 'DISC', 'asmidah', '2009-08-14 10:15:52.000', 'asmidah', '2021-07-01 12:37:43.150', 'R01', 'Tutup Laporan / Close log', 'C', 'NO', NULL),
            ('03', 'ESCL', 'INCIDENT ESCALATION', 'INCIDENT WILL ESCALATE / TRANSFER TO ANOTHER GROUP. INTERNAL OR EXTERNAL. E.G CSE,VENDOR, TELCOS,NMS,PROJECT TEAM OR ANY OTHER GROUP', 'MAJOR', 'asmidah', '2020-12-01 10:15:52.143', 'asmidah', '2021-07-01 12:37:53.373', 'R01', NULL, 'C', 'YES', 'OPT2'),
            ('04', 'INTR', 'INTERNAL REMARK', 'FAILED TO INFORM INTERNAL GROUP. E.G CSE,NMS,PROJECT TEAM', 'DISC', 'asmidah', '2020-12-01 10:15:52.143', 'asmidah', '2020-12-01 10:15:52.167', 'R01', NULL, 'I', 'NO', NULL),
            ('05', 'ONST', 'ONSITE', 'CSE,OSS, VENDOR, IA ARRIVED AS CUSTOMER SITE', 'MAJOR', 'asmidah', '2020-12-01 10:15:52.147', 'asmidah', '2020-12-16 16:58:39.897', 'R01', 'Onsite', 'I', 'NO', NULL),
            ('08', 'INIT', 'INITIAL RESPONSE', 'Initial Response', 'MAJOR', 'asmidah', '2020-12-01 10:15:52.147', 'asmidah', '2020-12-01 10:15:52.167', 'R01', NULL, 'C', 'NO', NULL),
            ('100', 'CNCLDUP', 'CANCEL / DUPLICATE', 'CANCEL / DUPLICATE', 'MAJOR', 'asmidah', '2020-12-01 10:15:52.147', 'asmidah', '2020-12-01 10:15:52.170', 'R01', 'Log Cancel / Duplicate. ', 'C', 'NO', NULL),
            ('101', 'WROUND', 'WORK AROUND', 'WORK AROUND SOLUTION FOR SSM', 'MAJOR', 'asmidah', '2020-12-01 10:15:52.147', 'faryna', '2024-03-01 14:56:12.213', 'R01', NULL, 'C', 'NO', 'OPT1'),
            ('102', 'SELESAI', 'SELESAI', 'MASALAH TELAH SELESAI', 'DISC', 'asmidah', '2020-12-18 16:57:18.587', 'asmidah', '2023-02-07 15:05:43.153', 'R02', 'Masalah selesai setelah CE.......', 'C', 'YES', 'OPT1'),
            ('72', 'DVWT', 'VENDOR WARRANTY', 'TIME TAKEN IN THE ACQUISITION OF EQUIPMENT UNDER VENDOR WARRANTY CLAIM', 'DISC', 'asmidah', '2020-12-01 10:15:52.150', 'asmidah', '2020-12-01 10:15:52.170', 'R01', ' Disclaimer. Under Warranty.', 'I', 'NO', NULL),
            ('80', 'UPDT', 'Incident Update', 'UPDATE STATUS TO CUSTOMER', 'MAJOR', 'asmidah', '2020-12-01 10:15:52.150', 'asmidah', '2020-12-01 10:15:52.173', 'R01', 'Update status to customer.', 'I', 'YES', 'OPT1'),
            ('81', 'DISC', 'DISCLAIMER', 'DISCLAIMER', 'DISC', 'asmidah', '2020-12-01 10:15:52.150', 'asmidah', '2020-12-01 10:15:52.173', 'R01', NULL, 'C', 'NO', NULL),
            ('82', 'SMSUP', 'SMSUP', 'SEND SMS UP', 'DISC', 'asmidah', '2020-12-01 10:15:52.153', 'asmidah', '2020-12-01 10:15:52.173', 'R01', NULL, 'I', 'NO', NULL),
            ('83', 'SMSDWN', 'SMS DOWN', 'SEND SMS DOWN', 'MAJOR', 'asmidah', '2020-12-01 10:15:52.153', 'asmidah', '2020-12-01 10:15:52.177', 'R01', NULL, 'I', 'NO', NULL),
            ('84', 'STARTD', 'START DISCLAIMER', 'START DISCLAIMER', 'DISC', 'asmidah', '2020-12-01 10:15:52.153', 'asmidah', '2020-12-01 10:15:52.177', 'R01', 'Running on ', 'I', 'NO', NULL),
            ('85', 'STOPD', 'STOP DISCLAIMER', 'STOP DISCLAIMER', 'DISC', 'asmidah', '2020-12-01 10:15:52.157', 'khadijah', '2022-12-28 11:03:49.357', 'R01', 'Line up. / Masalah selesai.', 'I', 'NO', NULL),
            ('92', 'RCA', 'RCA', 'ROOT CAUSE ANALYSIS', 'MAJOR', 'asmidah', '2020-12-01 10:15:52.157', 'asmidah', '2020-12-01 10:15:52.177', 'R01', NULL, 'P', 'NO', NULL),
            ('93', 'VRFY', 'VERIFY', 'VERIFIED WITH USER / CUSTOMER', 'DISC', 'asmidah', '2020-12-01 10:15:52.157', 'asmidah', '2020-12-01 10:15:52.180', 'R01', 'Verified with user ', 'C', 'NO', NULL),
            ('94', 'PROG', 'PROGRESS STATUS', 'ANY REMARK OR INFORMATION FOR PROGRESS INCIDENT LOG', 'MAJOR', 'asmidah', '2020-12-01 10:15:52.157', 'asmidah', '2020-12-01 10:15:52.180', 'R01', NULL, 'C', 'NO', NULL),
            ('95', 'FRST', 'FIRST LEVEL', 'FIRST LEVEL TROUBLESHOOTING', 'MAJOR', 'asmidah', '2020-12-01 10:15:52.160', 'asmidah', '2020-12-01 10:15:52.180', 'R01', NULL, 'I', 'NO', NULL),
            ('96', 'RPLC', 'PART / ASSET REPLACEMENT', 'PART / ASSET REPLACEMENT', 'MAJOR', 'asmidah', '2020-12-01 10:15:52.160', 'asmidah', '2020-12-01 10:15:52.183', 'R01', 'Replace ? , SN:  ? Model : ?', 'I', 'NO', NULL),
            ('97', 'MAJOR', 'MAJOR', 'MAJOR INCIDENT - TECHNICIAN WILL RECEIVE EMAIL', 'MAJOR', 'asmidah', '2020-12-01 10:15:52.160', 'asmidah', '2020-12-01 10:15:52.183', 'R01', NULL, 'I', 'YES', 'OPT3'),
            ('98', 'ENDUPDT', 'Incident update', 'UPDATE STATUS TO CUSTOMER AFTER ACTR', 'DISC', 'asmidah', '2020-12-01 10:15:52.163', 'asmidah', '2020-12-01 10:15:52.183', 'R01', 'Update status to customer.', 'C', 'YES', 'OPT1'),
            ('99', 'RESP', 'INITIAL RESPONSE', 'HUBUNGI USER UNTUK PENGESAHAN MASALAH', 'MAJOR', 'asmidah', '2020-12-01 10:15:52.163', 'asmidah', '2023-02-07 15:09:11.447', 'R02', 'Hubungi user', 'I', 'NO', NULL);
        ");
    }
}
