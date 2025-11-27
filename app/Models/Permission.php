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

    public static  function getPermission($colum = 'name'){
        $user_role = UserRole::where('user_id',Auth::user()->id)->pluck('role_id');
        $data = self::whereHas('roles', function ($query)use($user_role) {
                      $query->whereIn('role.id',$user_role); 
                    })
                    ->when($colum =='module_id', function ($query) {
                        return $query->whereRaw('LOWER(name) LIKE ?', ["%index%"]); 
                    })
                    ->pluck($colum);

        return $data;
    }

    public static function getParentUserDetails(){
        $user_role = UserRole::where('user_id',Auth::user()->id)->pluck('role_id');

        $data = self::whereHas('roles', function ($query)use($user_role) {
                      $query->whereIn('role.id',$user_role); 
                    })
                    ->pluck('module_id');
        
        $data = Module::whereNull('module_id')->whereIn('id',$data)->pluck('id');
                    // dd($data);
        return $data;
    }
}
