<?php

namespace App\Models;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Model;

class IncidentPenalty extends BaseModel
{
    protected $table = 'incident_penalty';

    protected $fillable = [ 
        'incident_id',
        'penalty_irt',
        'penalty_ort',
        'penalty_prt',
        'penalty_vprt',
    ];

}
