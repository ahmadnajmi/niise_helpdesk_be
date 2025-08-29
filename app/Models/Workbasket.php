<?php

namespace App\Models;

use App\Models\BaseModel;
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
}
