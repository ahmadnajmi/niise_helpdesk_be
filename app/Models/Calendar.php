<?php

namespace App\Models;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Model;

class Calendar extends BaseModel
{
    protected $fillable = [ 
        'name',
        'start_date',
        'end_date',
        'state_id',
        'description',
        'is_active',
    ];

    public function stateDescription(){
        return $this->hasOne(RefTable::class,'ref_code','state_id')->where('code_category', 'state');
    }

    public function getStateDesc($state_id){
        $data = RefTable::where('code_category','state')->whereIn('ref_code',json_decode($state_id))->pluck('name');

        return $data;
    }
}
