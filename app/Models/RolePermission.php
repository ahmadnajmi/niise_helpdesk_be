<?php

namespace App\Models;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Model;

class RolePermission extends BaseModel
{
    protected $table = 'role_permissions';

    protected $fillable = [ 
        'role_id',
        'permission_id',
    ];

    public function permission(){
        return $this->hasOne(Permission::class,'id','permission_id');
    }
}
