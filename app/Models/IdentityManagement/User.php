<?php

namespace App\Models\IdentityManagement;

use Illuminate\Database\Eloquent\Model;
use Laravel\Passport\HasApiTokens;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Auth;

class User extends Authenticatable
{
    use HasApiTokens;
    protected $connection = 'oracle_identity_management'; 

    protected $table = 'user';

    protected $fillable = [
        'id',
        'name',
        'password',
        'position',
        'branch_id',
        'email',
        'phone_no',
        'category_office'
    ];

    public function branch(){
        return $this->hasOne(Branch::class,'id','branch_id');
    }

    public function role(){
        return $this->hasMany(UserRole::class,'user_id','id');
    }
    
    public static function findForPassport($email){
        return static::where('email', $email)->first();
    }
    
    
    public static  function getUserDetails(){
        $data = self::select('id','name','position','branch_id','email','phone_no','category_office')
                    ->with(['branch' => function ($query) {
                        $query->select('id', 'name','state','location');
                    }])
                    ->where('id',Auth::user()->id)
                    ->first();

        return $data;
    }

}
