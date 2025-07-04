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
        'solution_notes'
    ];
}
