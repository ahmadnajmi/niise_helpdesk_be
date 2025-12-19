<?php

namespace App\Models;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Model;

class IncidentPenalty extends BaseModel
{
    protected $table = 'incident_penalty';

    protected $fillable = [ 
        'incident_id',
        'total_response_time_penalty_price',
        'total_response_time_penalty_minute',

    ];

}
