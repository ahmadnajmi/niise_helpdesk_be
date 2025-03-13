<?php

namespace App\Models;

use App\Models\BaseModel;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

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

    public function createdBy(){
        return $this->hasOne(User::class,'id','created_by');
    }

    public function updatedBy(){
        return $this->hasOne(User::class,'id','updated_by');
    }
}
