<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ActionCode extends Model
{
    public $timestamps = false;

    public $incrementing = false;

    protected $table = 'refAction';

    protected $primaryKey = 'ac_code';

    public function getMaxid(){
        return $this->select(DB::raw('cast(ac_code as int)'))
                ->max(DB::raw('cast(ac_code as int)'));
    }

    public function status(){
        // return $this->belongsTo(Status::class, 'ac_status_rec', 'ID');
        // return Status::where('ID', $this->ac_status_rec)->first();
        return $this->hasOne(Status::class, 'ac_status_rec', 'ID');
    }


}
