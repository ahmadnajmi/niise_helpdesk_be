<?php

namespace App\Models;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Model;

class Category extends BaseModel
{
    protected $table = 'categories';

    protected $fillable = [ 
        'abbreviation',
        'issue_level',
        'description',
        'is_active',
    ];

    public function issueLevelDescription(){
        return $this->hasOne(RefTable::class,'ref_code','issue_level')->where('code_category', 'issue_level');
    }
}
