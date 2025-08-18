<?php

namespace App\Models;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Model;

class Incident extends BaseModel
{
    protected $table = 'incidents';

    protected $fillable = [ 
        'incident_no',
        'code_sla',
        'incident_date',
        'branch_id',
        'category_id',
        'complaint_id',
        'information',
        'knowledge_base_id',
        'received_via',
        'report_no',
        'incident_asset_type',
        'date_asset_loss',
        'date_report_police',
        'report_police_no',
        'asset_siri_no',
        'group_id',
        'operation_user_id',
        'appendix_file',
        'asset_file',
        'end_date',
        'status',
        'asset_parent_id',
        'asset_component_id',

    ];

    protected $casts = [
        'incident_date' => 'datetime:Y-m-d',
        'date_asset_loss' => 'datetime:Y-m-d',
        'date_report_police' => 'datetime:Y-m-d',
        'end_date' => 'datetime:Y-m-d',
    ];


    const OPEN = 1;
    const RESOLVED = 2;
    const CLOSED = 3;
    const CANCEL_DUPLICATE = 4;
    const ON_HOLD = 5;


    public function branch(){
        return $this->hasOne(Branch::class,'id','branch_id');
    }

    public function receviedViaDescription(){
        return $this->hasOne(RefTable::class,'ref_code','received_via')->where('code_category', 'received_via');
    }

    public function incidentAssetTypeDescription(){
        return $this->hasOne(RefTable::class,'ref_code','incident_asset_type')->where('code_category', 'incident_asset_type');
    }

    public function complaint(){
        return $this->hasOne(Complaint::class,'id','complaint_id');
    }

    public function sla(){
        return $this->hasOne(Sla::class,'code','code_sla');
    }

    public function incidentSolution(){
        return $this->hasMany(IncidentSolution::class, 'incident_id','id')->orderBy('created_at','asc');
    }

    public function categoryDescription(){
        return $this->hasOne(Category::class,'id','category_id');
    }

    public function group(){
        return $this->hasOne(Group::class, 'id','group_id');
    }
    
    public function operationUser(){
        return $this->hasOne(User::class,'id','operation_user_id');
    }

    public function statusDesc(){
        return $this->hasOne(RefTable::class,'ref_code','status')->where('code_category', 'incident_status');
    }
}
