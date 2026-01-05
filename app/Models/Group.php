<?php

namespace App\Models;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Group extends BaseModel
{
    protected $table = 'groups';

    protected $fillable = [ 
        'name',
        'description',
        'is_active',
    ];

    protected array $filterable = ['name','description','is_active'];


    public function userGroup(){
        return $this->hasMany(UserGroup::class,'groups_id');
    }

    public function userGroupAccess(){
        return $this->hasMany(UserGroupAccess::class,'groups_id','id');
    }

    public function users(){
        return $this->hasManyThrough(User::class, UserGroup::class, 'groups_id', 'id', 'id', 'user_id');
    }
 
    public function incidents(){
        return $this->hasMany(Incident::class,'assign_group_id','id');
    }
    
}
