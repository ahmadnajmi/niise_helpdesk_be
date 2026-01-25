<?php

namespace App\Models;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Model;

class Report extends BaseModel
{
    protected $table = 'report';

    protected $fillable = [ 
        'name',
        'code',
        'output_name',
        'file_name',
        'path',
        'is_default',
    ];

}
