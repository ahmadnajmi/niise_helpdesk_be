<?php

namespace App\Models;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Model;

class UserGroup extends BaseModel
{
    protected $table = 'user_groups';

    protected $fillable = [ 
        'groups_id',
        'ic_no',
    ];

    public function userDetails(){
        return $this->belongsTo(User::class, 'ic_no','ic_no');
    }

    public function groupDetails(){
        return $this->belongsTo(Group::class, 'groups_id');
    }
}
