<?php

namespace App\Models;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Model;

class OperatingTime extends BaseModel
{
    protected $table = 'operating_times';

    protected $fillable = [ 
        'day',
        'duration',
        'operation_start',
        'operation_end',
        'is_active',
    ];

    public function dayDescription(){
        return $this->hasOne(RefTable::class,'ref_code','day')->where('code_category', 'day');
    }

    public function durationDescription(){
        return $this->hasOne(RefTable::class,'ref_code','duration')->where('code_category', 'duration');
    }

    public function getOperationStartAttribute($value){
        return date('H:i', strtotime($value));
    }

    public function getOperationEndAttribute($value){
        return date('H:i', strtotime($value));
    }

    public function setOperationStartAttribute($value){
        $this->attributes['operation_start'] = date('Y-m-d H:i:s', strtotime("1970-01-01 $value"));
    }

    public function setOperationEndAttribute($value){
        $this->attributes['operation_end'] = date('Y-m-d H:i:s', strtotime("1970-01-01 $value"));
    }
}
