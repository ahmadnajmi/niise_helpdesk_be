<?php

namespace App\Models;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Model;

class SlaCategory extends BaseModel
{
    protected $table = 'sla_category';

    protected $fillable = [ 
        'sla_id',
        'category_id',
    ];
}
