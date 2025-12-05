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

        'resolution_time',
        'resolution_time_type',
        'resolution_time_penalty',
    ];  

    public function responseTimeTypeDescription(){
        return $this->hasOne(RefTable::class,'ref_code','response_time_type')->where('code_category', 'sla_type');
    }

    public function slaTemplate(){
        return $this->hasOne(SlaTemplate::class,'id','sla_template_id');
    }
}