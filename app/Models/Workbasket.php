<?php

namespace App\Models;

use App\Models\BaseModel;
use Workbench\App\Models\User;
use Illuminate\Database\Eloquent\Model;

class Workbasket extends BaseModel
{
    protected $table = 'workbasket';

    protected $fillable = [
        'date',
        'incident_id',
        'handle_by',
        'status',
    ];

    protected $casts = [
        'date' => 'datetime:Y-m-d',
    ];

    const NEW = 1;
    const IN_PROGRESS = 2;
    const OPENED = 3;

    public function incident(){
        return $this->belongsTo(Incident::class, 'incident_id');
    }

     public function statusDesc(){
        return $this->hasOne(RefTable::class,'ref_code','status')->where('code_category', 'workbasket_status');
    }
    
}
