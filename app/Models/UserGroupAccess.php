<?php

namespace App\Models;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Model;

class UserGroupAccess extends BaseModel
{
    protected $table = 'user_group_access';

    protected $fillable = [ 
        'groups_id',
        'ic_no',
    ];

    public function userDetails(){
        return $this->hasOne(User::class,'ic_no','ic_no');
    }

    public function groupDetails(){
        return $this->hasOne(Group::class,'id','groups_id');
    }
}
