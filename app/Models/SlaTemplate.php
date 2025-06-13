<?php

namespace App\Models;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Model;

class SlaTemplate extends BaseModel
{
    protected $table = 'sla_template';

    protected $fillable = [
        'code',
        'severity_id',
        'service_level',
        'timeframe_channeling',
        'timeframe_channeling_type',
        'timeframe_incident',
        'timeframe_incident_type',
        'response_time_reply',
        'response_time_reply_type',
        'timeframe_solution',
        'timeframe_solution_type',
        'response_time_location',
        'response_time_location_type',
        'notes'
    ];

    public function severityDescription(){
        return $this->hasOne(RefTable::class,'ref_code','severity_id')->where('code_category', 'severity');
    }

    public function channelingTypeDescription(){
        return $this->hasOne(RefTable::class,'ref_code','timeframe_channeling_type')->where('code_category', 'sla_type');
    }

    public function incidentTypeDescription(){
        return $this->hasOne(RefTable::class,'ref_code','timeframe_incident_type')->where('code_category', 'sla_type');
    }

    public function replyTypeDescription(){
        return $this->hasOne(RefTable::class,'ref_code','response_time_reply_type')->where('code_category', 'sla_type');
    }

    public function solutionTypeDescription(){
        return $this->hasOne(RefTable::class,'ref_code','timeframe_solution_type')->where('code_category', 'sla_type');
    }

    public function locationTypeDescription(){
        return $this->hasOne(RefTable::class,'ref_code','response_time_location_type')->where('code_category', 'sla_type');
    }
}