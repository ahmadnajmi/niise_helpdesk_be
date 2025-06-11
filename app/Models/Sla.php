<?php

namespace App\Models;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Model;

class Sla extends BaseModel
{
    protected $table = 'sla';

    protected $fillable = [ 
        'code',
        'category_id',
        'state_id',
        'branch_id',
        'start_date',
        'end_date',
        'sla_template_id',
        'group_id',
        'is_active'
    ];

    public function stateDescription(){
        return $this->hasOne(RefTable::class,'ref_code','state_id')->where('code_category', 'state');
    }

    public function branch(){
        return $this->hasOne(Branch::class,'id','branch_id');
    }

    public function slaTemplate(){
        return $this->hasOne(SlaTemplate::class,'id','sla_template_id');
    }

    public function group(){
        return $this->hasOne(Group::class,'id','group_id');
    }
}
