<?php

namespace App\Models;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class UserRole extends BaseModel
{
    protected $table = 'user_role';
    protected $primaryKey = null;

    public $timestamps = false;
    public $incrementing = false;

    protected $fillable = [ 
        'role_id',
        'user_id',
    ];

    public function roleDetails(){
        return $this->hasOne(Role::class,'id','role_id');
    }

    public function userDetails(){
        return $this->hasOne(User::class,'id','user_id');
    }

    public static  function getUserDetails(){
        $data = self::select('role_id')
                    ->with(['roleDetails' => function ($query) {
                        $query->select('id', 'name','role');
                    }])
                    ->where('user_id',Auth::user()->id)
                    ->first();

        return $data;
    }

}
