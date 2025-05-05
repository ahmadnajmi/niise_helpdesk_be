<?php

namespace App\Models;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Permission extends BaseModel
{
    protected $table = 'permissions';

    public $timestamps = false;

    protected $fillable = [ 
        'module_id',
        'name',
        'description'
    ];

    public function module(){
        return $this->belongsTo(Module::class);
    }

    public function roles(){
        return $this->belongsToMany(Role::class, 'role_permissions');
    }

    public static  function getUserDetails($colum = 'name'){
        $user_role = UserRole::where('user_id',Auth::user()->id)->pluck('role_id');
        $data = self::whereHas('roles', function ($query)use($user_role) {
                      $query->whereIn('role.id',$user_role); 
                    })
                    ->pluck($colum);

        return $data;
    }
}
