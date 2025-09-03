<?php

namespace App\Models;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Model;

class Role extends BaseModel
{
    protected $table = 'role';

    protected $fillable = [ 
        'name',
        'name_en',
        'code',
        'description',
        'is_active',
    ];

    const JIM = 1;
    const NOC_SOC_AOC = 2;
    const FRONTLINER = 3;
    const ICT_SV = 4;
    const BTMR = 5;
    const CONTRACTOR = 6;

    public function users(){
        return $this->belongsToMany(User::class, 'user_role');
    }

    public function permissions(){
        return $this->belongsToMany(Permission::class, 'role_permissions');
    }

    public function modules(){
        return $this->permissions()->whereHas('module', function ($query) {
            $query->whereNull('module_id'); 
        })->with('module');
    }
    
    public function userRole(){
        return $this->hasOne(UserRole::class, 'role_id','id');
    }

    public function getTranslatedNameAttribute(){
        $locale = request()->header('Accept-Language', 'en');
        
        return $this->{"name_{$locale}"} ?? $this->name;
    }
}
