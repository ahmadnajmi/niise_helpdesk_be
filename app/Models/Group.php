<?php

namespace App\Models;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Model;

class Group extends BaseModel
{
    protected $table = 'groups';

    protected $fillable = [ 
        'name',
        'description',
        'is_active',
    ];
}
