<?php

namespace App\Models;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Model;

class Category extends BaseModel
{
    protected $table = 'categories';

    protected $fillable = [ 
        'category_id',
        'name',
        'level',
        'code',
        'description',
        'is_active',
    ];
}
