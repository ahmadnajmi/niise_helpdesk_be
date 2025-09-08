<?php

namespace App\Models;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Model;

class Category extends BaseModel
{
    protected $table = 'categories';

    protected $fillable = [ 
        'category_id',
        'name',
        'level',
        'code',
        'description',
        'is_active',
    ];

    const MOBILE = 'MOBILE';
    const SISTEM = 'SISTEM';


    public function mainCategory(){
        return $this->hasOne(Category::class, 'id','category_id');
    }

    public function childCategory(){
        return $this->hasMany(Category::class,'category_id','id');
    }


    public function sla(){
        return $this->hasOne(Sla::class, 'category_id','id');
    }
}
