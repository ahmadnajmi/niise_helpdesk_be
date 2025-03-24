<?php

namespace App\Models;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Model;

class UserGroup extends BaseModel
{
    protected $table = 'user_groups';

    protected $fillable = [ 
        'groups_id',
        'user_id',
    ];
}
