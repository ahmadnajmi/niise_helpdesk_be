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

    protected array $filterable = ['code','description','name','is_active'];

    public function childCategoryRecursive(){
        return $this->hasMany(Category::class, 'category_id', 'id')
                    ->with('childCategoryRecursive');
    }

    public function mainCategory(){
        return $this->hasOne(Category::class, 'id','category_id');
    }

    public function childCategory(){
        return $this->hasMany(Category::class,'category_id','id');
    }

    public function sla(){
        return $this->hasOne(Sla::class, 'category_id','id');
    }

    public function incidents(){
        return $this->hasOne(Incident::class, 'category_id','id');
    }
}


