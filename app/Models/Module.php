<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Module extends BaseModel
{
  protected $table = 'module';

  protected $fillable = [ 
    'module_id',
    'name',
    'name_en',
    'description',
    'is_active',
  ];

  public function subModule()
  {
    return $this->hasMany(Module::class, 'module_id','id');
  }

  public function permissions()
  {
    return $this->hasMany(Permission::class);
  }

  public function getTotalSubModuleCountAttribute()
  {
    return $this->subModule->sum(fn($sub) => 1 + $sub->total_sub_module_count);
  }

  public function getTranslatedNameAttribute()
  {
    $locale = request()->header('Accept-Language', 'en');
    
    return $this->{"name_{$locale}"} ?? $this->name;
  }

  public function roles()
  {
    return $this->hasManyThrough(
      RolePermission::class, 
      Permission::class,     
      'module_id',           
      'permission_id',       
      'id',                  
      'id'                   
    );
  }
}
