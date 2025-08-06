<?php

namespace App\Models;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Model;

class IncidentSolution extends BaseModel
{
    protected $table = 'incident_solutions';

    protected $fillable = [ 
        'incident_id',
        'group_id',
        'operation_user_id',
        'report_contractor_no',
        'action_codes',
        'notes',
        'solution_notes',
        'status'

    ];

    public function actionCodes(){
        return $this->hasOne(ActionCode::class,'nickname','action_codes');
    }

    public function statusDesc(){
        return $this->hasOne(RefTable::class,'ref_code','status')->where('code_category', 'incident_resolution_status');
    }
}
