<?php

namespace App\Models;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Incident extends BaseModel
{
    use HasFactory;
    protected $table = 'incidents';

    protected $fillable = [ 
        'incident_no',
        'code_sla',
        'incident_date',
        'barcode',
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
        'expected_end_date',
        'actual_end_date',
        'status',
        'asset_parent_id',
        'asset_component_id',
        'sla_version_id',
        'service_recipient_id'
    ];

    protected $casts = [
        'incident_date' => 'datetime:Y-m-d',
        'date_asset_loss' => 'datetime:Y-m-d',
        'date_report_police' => 'datetime:Y-m-d',
        'expected_end_date' => 'datetime:Y-m-d',
        'actual_end_date' => 'datetime:Y-m-d',
    ];

    const OPEN = 1;
    const RESOLVED = 2;
    const CLOSED = 3;
    const CANCEL_DUPLICATE = 4;
    const ON_HOLD = 5;

    protected static function booted(){
        static::creating(function ($model) {
            $get_incident = Incident::orderBy('incident_no','desc')->first();

            if($get_incident){
                $code = $get_incident->incident_no;

                $old_code = substr($code, -5);

                $incremented = (int)$old_code + 1;

                $next_number = str_pad($incremented, 5, '0', STR_PAD_LEFT);
            }
            else{
                $next_number = '00001';
            }

            $model->incident_no = 'TN'.date('Ymd').$next_number;
        });
    }

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

    public function slaVersion(){
        return $this->hasOne(SlaVersion::class,'id','sla_version_id');
    }

    public function sla(){
        return $this->hasOne(Sla::class,'code','code_sla');
    }

    public function incidentResolution(){
        return $this->hasMany(IncidentResolution::class, 'incident_id','id')->orderBy('created_at','asc');
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

    public function serviceRecipient(){
        return $this->hasOne(User::class,'id','service_recipient_id');
    }

    public function statusDesc(){
        return $this->hasOne(RefTable::class,'ref_code','status')->where('code_category', 'incident_status');
    }

    public function workbasket(){
        return $this->hasOne(Workbasket::class,'incident_id','id');
    }

    protected function calculateCountDownSettlement(): Attribute{
        return Attribute::get(function () {
            $diff = $this->incident_date->diff($this->expected_end_date);

            return $diff->d .' Hari : ' . $diff->h . ' Jam : ' .$diff->i  .' Minit';
        });
    }

    protected function calculateBreachTime(): Attribute{
        return Attribute::get(function () {

            if (!$this->actual_end_date || $this->actual_end_date->lessThanOrEqualTo($this->expected_end_date)) {
                return '00 Hari : 00 Jam : 00 Minit';
            }
            else{
                $diff = $this->expected_end_date->diff($this->actual_end_date);

                return $diff->d .' Hari : ' . $diff->h . ' Jam : ' .$diff->i  .' Minit';
            }
        });
    }
}
