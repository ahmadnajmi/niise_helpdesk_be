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

  public function permissions()
  {
      return $this->belongsToMany(Permission::class, 'role_permissions');
  }

  public function modules()
  {
      return $this->permissions()->whereHas('module', function ($query) {
          $query->whereNull('module_id'); 
      })->with('module');
  }

  // public function modules()
  // {
  //     return $this->permissions()->with(['module' => function($query)  {
  //       // Query the name field in status table
  //       $query->where('module_id',null); // '=' is optional
  //   }]);
  // }
 
  // public function modules()
  //   {
  //       return $this->permissions()->with('module');
        
  //   }

  

    // public function modules()
    // {
    //     return $this->hasManyThrough(Module::class, Permission::class, 'id', 'id', 'id', 'module_id');
    // }
}
