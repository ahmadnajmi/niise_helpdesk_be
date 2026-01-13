<?php

namespace App\Models;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Module extends BaseModel
{
    protected $table = 'module';

    protected $fillable = [ 
        'module_id',
        'name',
        'name_en',
        'svg_path',
        'description',
        'is_active',
        'code',
        'order_by'
    ];

    public function subModule(){
        return $this->hasMany(Module::class, 'module_id','id')->where('is_active',true);
    }

    public function subModuleRecursive(){
        return $this->subModule()->with('subModuleRecursive','route');
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

        $get_permission = Permission::getPermission('module_id');

        $data = self::select('id','code','module_id','name','name_en','svg_path')
                        ->with([
                            'subModuleRecursive' => function ($q) use ($get_permission) {
                                $q->whereIn('id', $get_permission)
                                ->with(['subModuleRecursive' => function ($q2) use ($get_permission) {
                                    $q2->whereIn('id', $get_permission);
                                }, 'route'])->orderBy('order_by', 'asc');
                            },
                            'route:id,module_id,name'
                        ])
                        ->whereNull('module_id')
                        ->where('is_active', true)
                        ->where(function ($q) use ($get_permission) {
                            $q->whereIn('id', $get_permission)
                            ->orWhereHas('subModule', function ($sub) use ($get_permission) {
                                $sub->whereIn('id', $get_permission);
                            });
                        })
                        ->orderBy('order_by', 'asc')
                        ->get();

    return $data;
    
        return $data;
    }

    public static function getUserDetailsbaru(){
        $get_permission = Permission::getUserDetails('module_id');

        $data = Module::select('id','code','module_id','name','name_en','svg_path')
                        ->with(['subModuleRecursive' => function ($query) use ($get_permission) {
                            $query->whereIn('id', $get_permission);
                        },
                        'route'])
                ->whereIn('id', $get_permission)
                ->whereNull('module_id')
                ->where('is_active', true)
                ->get();

        $grouped = $data->mapWithKeys(function ($module) {
            $return =  [$module->code => [$module]];

            if(count($module->subModuleRecursive) > 0) {

                foreach($module->subModuleRecursive as $sub_module){
                    $return[$module->code][$sub_module->code] =  $sub_module;
                }
            }
            return $return;
        });

        return $grouped;

    }
}
