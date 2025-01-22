<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends BaseModel
{
  protected $table = 'role';

  protected $fillable = [ 
    'name',
    'description',
    'is_active',
  ];

  public function permission()
  {
      return $this->hasMany(Permission::class,'role_id','id');
  }
}
