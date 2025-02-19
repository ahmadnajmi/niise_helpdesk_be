<?php

namespace App\Models;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Model;

class Role extends BaseModel
{
    protected $table = 'role';

    protected $fillable = [ 
        'name',
        'description',
        'is_active',
    ];

    public function permissions(){
        return $this->belongsToMany(Permission::class, 'role_permissions');
    }

    public function modules(){
        return $this->permissions()->whereHas('module', function ($query) {
            $query->whereNull('module_id'); 
        })->with('module');
    }
    
    public function userRole(){
        return $this->hasMany(UserRole::class, 'role_id','id');
    }
}
