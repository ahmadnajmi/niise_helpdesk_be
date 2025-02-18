<?php

namespace App\Models;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Module extends BaseModel
{
    protected $table = 'niise.module';

    public $timestamps = false;

    protected $fillable = [ 
       'module_id',
        'name',
        'name_en',
        'svg_path',
        'description',
        'is_active',
    ];

    public function subModule(){
        return $this->hasMany(Module::class, 'module_id','id');
    }

    public function permissions(){
        return $this->hasMany(Permission::class);
    }

    public function roles(){
        return $this->hasManyThrough(RolePermission::class,Permission::class,'module_id','permission_id','id','id');
    }

    public function route(){
        return $this->hasOne(Permission::class,'module_id','id')->where('name', 'like', '%index%');
    }

    public function getTotalSubModuleCountAttribute(){
        return $this->subModule->sum(fn($sub) => 1 + $sub->total_sub_module_count);
    }

    public function getTranslatedNameAttribute(){
        $locale = request()->header('Accept-Language', 'en');
        
        return $this->{"name_{$locale}"} ?? $this->name;
    }
    
    public static  function getUserDetails(){

        $get_permission = Permission::getUserDetails('module_id');

        $data = self::select('id','module_id','name','name_en','svg_path')
                    ->with(['subModule' => function ($query) {
                        $query->select('id','module_id', 'name','name_en','svg_path')
                            ->with(['subModule' => function ($query) {
                                $query->select('id','module_id', 'name','name_en','svg_path')
                                    ->with(['route' => function ($query) {
                                        $query->select('id','module_id', 'name');
                                }]);
                            }])
                            ->with(['route' => function ($query) {
                                $query->select('id','module_id', 'name');
                        }]);
                    }])
                    ->with(['route' => function ($query) {
                        $query->select('id','module_id', 'name');
                    }])
                    ->whereIn('id',$get_permission)
                    ->whereNull('module_id')
                    ->get();
    
        return $data;
    }
}
