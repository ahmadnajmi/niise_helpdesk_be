<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Module extends BaseModel
{
  protected $table = 'module';

  protected $fillable = [ 
    'module_id',
    'name',
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
}
