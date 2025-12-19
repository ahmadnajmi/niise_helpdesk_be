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

    public function userDetails(){
        return $this->belongsTo(User::class, 'user_id');
    }

    public function groupDetails(){
        return $this->belongsTo(Group::class, 'groups_id');
    }
}
