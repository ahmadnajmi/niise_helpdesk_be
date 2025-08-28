<?php

namespace App\Models;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Model;

class workbasket extends BaseModel
{
    protected $table = 'workbasket';

    protected $fillable = [
        'date',
        'incident_no',
        'handle_by',
        'status',
    ];
}
