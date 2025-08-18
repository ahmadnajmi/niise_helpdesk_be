<?php

namespace App\Models;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Model;

class SlaVersion extends BaseModel
{
    protected $table = 'sla_version';

    protected $fillable = [
        'sla_template_id',
        'version',

        'response_time',
        'response_time_type',
        'response_time_penalty',

        'resolution_time',
        'resolution_time_type',
        'resolution_time_penalty',
    ];  
}