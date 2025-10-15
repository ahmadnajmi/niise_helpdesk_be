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

    public function scopeSearch($query, $keyword){
        if (!empty($keyword)) {
            $keyword = strtolower($keyword);
            $lang = substr(request()->header('Accept-Language'), 0, 2); 

            $query->where(function($q) use ($keyword,$lang) {
                $q->whereRaw('LOWER(explanation) LIKE ?', ["%{$keyword}%"]);
                $q->orWhereRaw('LOWER(keywords) LIKE ?', ["%{$keyword}%"]);
                $q->orWhereRaw('LOWER(solution) LIKE ?', ["%{$keyword}%"]);
            });
        }
        return $query;
    }
}
