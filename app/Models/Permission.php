<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Permission extends BaseModel
{
  protected $table = 'permissions';
  public $timestamps = false;


  protected $fillable = [ 
    'module_id',
    'name',
  ];
  

  public function module()
    {
        return $this->belongsTo(Module::class);
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_permissions');
    }
}
