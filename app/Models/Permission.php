<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Permission extends BaseModel
{
  protected $table = 'permissions';

  protected $fillable = [ 
    'role_id',
    'sub_module_id',
    'allowed_list',
    'allowed_create',
    'allowed_view',
    'allowed_update',
    'allowed_delete',
  ];

  public function subModule()
  {
    return $this->hasOne(SubModule::class, 'id', 'sub_module_id');
  }
}
