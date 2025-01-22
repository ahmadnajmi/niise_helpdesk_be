<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ServiceLevel extends Model
{
    public $timestamps = false;

    public $incrementing = false;

    protected $table = 'HD_SLA';

    protected $primaryKey = 'sl_sla_code';

    public $keyType = 'string';

    public function getSlaIndex(){
        $result = $this->select('sl_sla_code', 'sl_customer_id', 'sl_category', 'sl_severity_lvl', 'sl_start_date', 'sl_end_date', 'sl_status_rec',
            'cc_customer_id', 'cc_category_code', 'cc_branch_code', 'cc_sla_code',
            'cb_customer_ID', 'cb_branchcode', 'cb_branch_Name',
            'Ct_Code', 'Ct_Abbreviation')
        ->leftJoin('HD_RelCust_Category', 'sl_sla_code', '=', 'HD_RelCust_Category.cc_sla_code')
        ->leftJoin('HD_Customer_Branch', 'sl_customer_id', '=', 'HD_Customer_Branch.cb_customer_ID')
        ->leftJoin('RefCategory', 'sl_category', '=', 'RefCategory.Ct_Code');
        log::info(json_encode($result));
        return $result;
    }
}
