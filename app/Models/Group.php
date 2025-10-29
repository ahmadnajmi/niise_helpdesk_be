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

    public function userGroup(){
        return $this->hasMany(UserGroup::class,'groups_id');
    }

    public function userGroupAccess(){
        return $this->hasMany(UserGroupAccess::class,'groups_id','id');
    }

    public function users(){
        return $this->hasManyThrough(User::class, UserGroup::class, 'groups_id', 'ic_no', 'id', 'ic_no');
    }
}
