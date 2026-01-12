<?php

namespace App\Models;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Model;

class SlaVersion extends BaseModel
{
    protected $table = 'sla_version';

    protected $fillable = [
        'sla_template_id',
        'version',

        'response_time',
        'response_time_type',
        'response_time_penalty',
        'response_time_penalty_type',

        'resolution_time',
        'resolution_time_type',
        'resolution_time_penalty',
        'resolution_time_penalty_type',

        'verify_resolution_time',
        'verify_resolution_time_type',
        'verify_resolution_time_penalty',
        'verify_resolution_time_penalty_type',

        'response_time_location',
        'response_time_location_type',
        'response_time_location_penalty',
        'response_time_location_penalty_type',
    ];  

    public function responseTimeTypeDescription(){
        return $this->hasOne(RefTable::class,'ref_code','response_time_type')->where('code_category', 'sla_type');
    }

    public function slaTemplate(){
        return $this->hasOne(SlaTemplate::class,'id','sla_template_id');
    }

    public function resolutionTimeTypeDescription(){
        return $this->hasOne(RefTable::class,'ref_code','resolution_time_type')->where('code_category', 'sla_type');
    }

    public function responseTimeLocationTypeDescription(){
        return $this->hasOne(RefTable::class,'ref_code','response_time_location_type')->where('code_category', 'sla_type');
    }

    public function responseTimePenaltyTypeDescription(){
        return $this->hasOne(RefTable::class,'ref_code','response_time_penalty_type')->where('code_category', 'sla_type');
    }

    public function responseTimeLocationPenaltyTypeDescription(){
        return $this->hasOne(RefTable::class,'ref_code','response_time_location_penalty_type')->where('code_category', 'sla_type');
    }

    public function resolutionTimePenaltyTypeDescription(){
        return $this->hasOne(RefTable::class,'ref_code','resolution_time_penalty_type')->where('code_category', 'sla_type');
    }

    public function verifyResolutionTimePenaltyTypeDescription(){
        return $this->hasOne(RefTable::class,'ref_code','verify_resolution_time_penalty_type')->where('code_category', 'sla_type');
    }

    public function verifyResolutionTimeTypeDescription(){
        return $this->hasOne(RefTable::class,'ref_code','verify_resolution_time_type')->where('code_category', 'sla_type');
    }

}