<?php

namespace App\Models;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Model;

class UserGroup extends BaseModel
{
    protected $table = 'user_groups';

    protected $fillable = [ 
        'groups_id',
        'user_type',
        'name',
        'email',
        'company_id',
        'ic_no',
    ];

    public function groupDetails(){
        return $this->belongsTo(Group::class, 'groups_id');
    }

    public function company(){
        return $this->belongsTo(Company::class, 'company_id');
    }
}
