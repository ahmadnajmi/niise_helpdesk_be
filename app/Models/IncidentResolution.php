<?php

namespace App\Models;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Model;

class IncidentResolution extends BaseModel
{
    protected $table = 'incident_resolution';

    protected $fillable = [ 
        'incident_id',
        'group_id',
        'operation_user_id',
        'report_contractor_no',
        'action_codes',
        'notes',
        'solution_notes',
    ];

    public function actionCodes(){
        return $this->hasOne(ActionCode::class,'nickname','action_codes');
    }

    public function incident(){
        return $this->hasOne(Incident::class,'id','incident_id');
    }

    public function group(){
        return $this->hasMany(Group::class,'id','group_id');
    }
}
