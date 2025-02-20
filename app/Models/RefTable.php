<?php

namespace App\Models;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Model;

class RefTable extends BaseModel
{
    protected $table = 'ref_table';

    protected $fillable = [ 
        'code_category',
        'ref_code',
        'name_en',
        'name',
    ];
}
