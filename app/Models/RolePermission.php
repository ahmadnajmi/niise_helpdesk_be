<?php

namespace App\Models;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Model;

class RolePermission extends BaseModel
{
    protected $table = 'role_permissions';
    protected $primaryKey = null;

    public $timestamps = false;
    public $incrementing = false;

    protected $fillable = [ 
        'role_id',
        'permission_id',
    ];
}
