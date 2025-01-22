<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubModule extends BaseModel
{
  protected $table = 'sub_module';

  protected $fillable = [ 
    'name',
    'module_id',
  ];
}
