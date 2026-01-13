<?php

namespace App\Models;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Model;

class Report extends BaseModel
{
    protected $table = 'report';

    protected $fillable = [ 
        'code',
        'jasper_file_name',
        'output_name',
    ];

}
