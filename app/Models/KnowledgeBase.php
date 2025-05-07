<?php

namespace App\Models;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Model;

class KnowledgeBase extends BaseModel
{
    protected $table = 'knowledge_bases';

    protected $fillable = [ 
        'category_id',
        'explanation',
        'keywords',
        'solution'
    ];

    public function categoryDescription(){
        return $this->hasOne(Category::class,'id','category_id');
    }
}
