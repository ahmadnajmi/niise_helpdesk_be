<?php

namespace App\Models;

use App\Models\Role;
use App\Models\Workbasket;
use Laravel\Passport\HasApiTokens;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Str;

class User extends Authenticatable
{
    use HasApiTokens;

    protected $table = 'users';
    public $incrementing = false;
    protected $keyType = 'string';
    public $usesUuid = true;
    
    protected $fillable = [
        'ic_no',
        'name',
        'nickname',
        'password',
        'first_time_password',
        'branch_id',
        'company_id',
        'email',
        'phone_no',
        'address',
        'postcode',
        'city',
        'state_id',
        'fax_no',
        'is_active',
        'user_type',
        'is_disabled',
        'failed_attempts',
        'two_fa_secret',
        'two_fa_enabled',
    ];

    protected $casts = [
        'two_fa_enabled' => 'datetime:Y-m-d',
    ];

    const FROM_IDM = 1;
    const FROM_HDS = 2;
    const FROM_COMPLAINT = 3;
    const SUPER_ADMIN_IC_NO = ['981230430001','981230430002','981230430003','981230430004','981230430005','981230430006'];

    protected static function boot() {
        parent::boot();

        static::creating(function ($model) {
            if (property_exists($model, 'usesUuid') && $model->usesUuid && empty($model->{$model->getKeyName()})) {
                $model->{$model->getKeyName()} = (string) Str::orderedUuid();
            }
        });
    }

    protected static $sortable = [
        'name' => 'name',
        'ic_no' => 'ic_no',
        'phone_no' => 'phone_no',
        'is_active' => 'is_active',
        'branch_id' => 'branch.name'
    ];

    public function scopeHideSuperAdmin($query){
        return $query->whereDoesntHave('roles', function ($query) {
            $query->where('role', Role::SUPER_ADMIN);
        })
        ->whereNotIn('ic_no', User::SUPER_ADMIN_IC_NO);
    }


    public function scopeSearch($query, $keyword){
        if (!empty($keyword)) {
            $keyword = strtolower($keyword);
            
            $query->where(function($q) use ($keyword) {
                $q->whereRaw('LOWER(users.ic_no) LIKE ?', ["%{$keyword}%"]);
                $q->orWhereRaw('LOWER(users.name) LIKE ?', ["%{$keyword}%"]);
                $q->orWhereRaw('LOWER(users.phone_no) LIKE ?', ["%{$keyword}%"]);

                $q->orWhereHas('branch', function ($search) use ($keyword) {
                    $search->whereRaw('LOWER(branch.name) LIKE ?', ["%{$keyword}%"]);
                });
                $q->orWhereHas('company', function ($search) use ($keyword) {
                    $search->whereRaw('LOWER(companies.name) LIKE ?', ["%{$keyword}%"]);
                });
                $q->orWhereHas('roles', function ($search) use ($keyword) {
                    $search->whereRaw('LOWER(role.name) LIKE ?', ["%{$keyword}%"]);
                });
            });
        }
        return $query;
    }

    public function scopeFilter($query){

        $query = $query
                        ->when(request('ic_no'), function ($query) {
                           $query->whereRaw('LOWER(ic_no) LIKE ?', ['%' . strtolower(request('ic_no')) . '%']);
                        })
                        ->when(request('name'), function ($query) {
                            $query->whereRaw('LOWER(name) LIKE ?', ['%' . strtolower(request('name')) . '%']);
                        })
                        ->when(request('phone_no'), function ($query) {
                            $query->where('phone_no',request('phone_no'));
                        })
                        ->when(request('branch_id'), function ($query) {
                            $query->where('branch_id',request('branch_id'));
                        })
                        ->when(request('company_id'), function ($query) {
                            $query->where('company_id',request('company_id'));
                        })
                        ->when(request('role_id'), function ($query) {
                            $query->whereHas('roles', function ($query)  {
                                $query->where('role_id',request('role_id'));
                            });
                        })
                        ->when(request()->has('is_active'), function ($query) {
                            $query->where('is_active',request('is_active') == true ? true : false);
                        });

        return $query;
    }

    public function scopeSortByField($query,$request){
        $hasSorting = false;

        foreach ($request->all() as $key => $direction) {

            if (Str::endsWith($key, '_sort')) {
                
                $field = str_replace('_sort', '', $key);
                $direction = strtolower($direction);
                $sortable = static::$sortable[$field] ?? null;

                if (!in_array($direction, ['asc', 'desc']) || !$sortable) {
                    continue;
                }
                $hasSorting = true;

              
                $query->orderBy($sortable, $direction);
            }
        }

        if (!$hasSorting) {
            $query->orderByDesc('updated_at');
        }

        return $query;
    }

    public function branch(){
        return $this->hasOne(Branch::class,'id','branch_id');
    }

    public function ssoToken(){
        return $this->hasOne(SsoSession::class,'user_id','id');
    }

    public function company(){
        return $this->hasOne(Company::class,'id','company_id');
    }

    public function roles(){
        return $this->belongsToMany(Role::class, 'user_role');
    }

    public function stateDescription(){
        return $this->hasOne(RefTable::class,'ref_code','state_id')->where('code_category', 'state');
    }

    public function findForPassport($ic_no) {
        return $this->where('ic_no', $ic_no)->first();
    }

    public function group(){
        return $this->hasMany(UserGroup::class,'user_id','id');
    }

    public function groupAccess(){
        return $this->hasMany(UserGroupAccess::class,'user_id','id');
    }


    public function workbaskets()
    {
        return $this->hasMany(Workbasket::class, 'handle_by');
    }

    // public function transformAudit(array $data): array {
    //     switch ($data['event']) {
    //         case 'created':
    //             $data['custom_label'] = 'Daftar User';
    //             break;

    //         case 'updated':
    //             $data['custom_label'] = 'Kemaskini User';
    //             break;

    //         case 'deleted':
    //             $data['custom_label'] = 'Padam User';
    //             break;
    //     }
    //     return $data;
    // }


    public static  function getUserDetails(){
        $data = self::select('id','name','position','branch_id','email','phone_no','category_office','first_time_password')
                    ->with(['branch' => function ($query) {
                        $query->select('id', 'name','state_id','location')
                            ->with(['stateDescription' => function ($query) {
                                $query->select('ref_code', 'name','name_en');
                            }]);
                    }])
                    ->where('id',Auth::user()->id)
                    ->first();

        return $data;
    }

    public static function getUserRole($id){
        $get_role = Role::select('id','name','role')->whereHas('userRole', function ($query)use($id) {
                      $query->where('user_id',$id); 
                    })
                    ->first();

        return $get_role;
    }

    public function getMaskedIcNoAttribute(){
        $value = $this->attributes['ic_no'];

        if($value){
            return str_repeat('*', strlen($value) - 4) . substr($value, -4);
        }

        return $value;
    }
}
