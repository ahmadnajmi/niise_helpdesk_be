<?php

namespace App\Models;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class ActionCode extends BaseModel 
{
    protected $table = 'action_codes';

    protected $fillable = [ 
        'name',
        'category',
        'abbreviation',
        'description',
        'is_active',
    ];
    

    public function categoryDescription(){
        return $this->hasOne(RefTable::class,'ref_code','category')->where('code_category', 'action_code_category');
    }

}
