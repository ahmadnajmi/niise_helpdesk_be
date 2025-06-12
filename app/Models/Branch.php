<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    //
    protected $table = 'branch';

    protected $fillable = [
        'name',
        'state',
        'category',
        'location',
    ];

    public function operatingTime(){
        return $this->hasMany(OperatingTime::class, 'branch_id','id');
    }
}
