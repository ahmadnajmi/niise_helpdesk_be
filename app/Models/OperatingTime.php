<?php

namespace App\Models;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OperatingTime extends BaseModel
{
    use HasFactory;

    protected $table = 'operating_times';

    protected $fillable = [ 
        'day_start',
        'day_end',
        'branch_id',
        'duration',
        'operation_start',
        'operation_end',
        'is_active',
    ];

    protected $casts = [
        'operation_start' => 'date:H:i:s',
        'operation_end'   => 'date:H:i:s',
    ];

    const NORMAL_DAY = 1;
    const HALF_DAY = 2;
    const WEEKEND = 3;


    public function branch(){
        return $this->hasOne(Branch::class,'id','branch_id');
    }

    public function daystartDescription(){
        return $this->hasOne(RefTable::class,'ref_code','day_start')->where('code_category', 'day');
    }

    public function dayendDescription(){
        return $this->hasOne(RefTable::class,'ref_code','day_end')->where('code_category', 'day');
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
        $this->attributes['operation_start'] = now()->format('Y-m-d') . ' ' . $value;
    }

    public function setOperationEndAttribute($value){
        $this->attributes['operation_end'] = now()->format('Y-m-d') . ' ' . $value;
    }
}
