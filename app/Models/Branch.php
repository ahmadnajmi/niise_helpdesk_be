<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class Branch extends Model
{
    //
    protected $table = 'branch';

    protected $fillable = [
        'name',
        'state_id',
        'category',
        'location',
    ];

    // public function operatingTime(){
    //     return $this->hasMany(OperatingTime::class, 'branch_id','id');
    // }

    public function stateDescription(){
        return $this->hasOne(RefTable::class,'ref_code','state_id')->where('code_category', 'state');
    }

    public function getOperatingTime($branch_id){
        $data = OperatingTime::whereRaw("JSON_EXISTS(branch_id, '\$[*] ? (@ == $branch_id)')")->get();

        return $data;
    }

    public function getOperatingTimeCount($branch_id){
        $data = OperatingTime::whereRaw("JSON_EXISTS(branch_id, '\$[*] ? (@ == $branch_id)')")->count();

        
        return $data;
    }
}
