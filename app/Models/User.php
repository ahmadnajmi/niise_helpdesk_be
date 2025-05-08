<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Laravel\Passport\HasApiTokens;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Auth;

class User extends Authenticatable
{
    use HasApiTokens;

    protected $table = 'user';

    protected $fillable = [
        'ic_no',
        'name',
        'nickname',
        'password',
        'position',
        'branch_id',
        'email',
        'phone_no',
        'category_office',
        'address',
        'postcode',
        'city',
        'state_id',
        'fax_no',
        'is_active',
    ];

    // protected $auditEvents = [
    //     'created' => 'Daftar User',
    //     'updated' => 'Kemaskini Use',
    //     'deleted' => 'Padam User'
    // ];

    public function branch(){
        return $this->hasOne(Branch::class,'id','branch_id');
    }

    public function role(){
        return $this->hasMany(UserRole::class,'user_id','id');
    }

    public function stateDescription(){
        return $this->hasOne(RefTable::class,'ref_code','state_id')->where('code_category', 'state');
    }
    
    public static function findForPassport($email){
        return static::where('email', $email)->first();
    }

    public function scopeFilter($query){

        $query->when(request('ic_no'), function ($query){
            $query->where('ic_no', request('ic_no')); 
        });

        return $query;
    }

    public function transformAudit(array $data): array {
        switch ($data['event']) {
            case 'created':
                $data['custom_label'] = 'Daftar User';
                break;

            case 'updated':
                $data['custom_label'] = 'Kemaskini User';
                break;

            case 'deleted':
                $data['custom_label'] = 'Padam User';
                break;
        }
        return $data;
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
