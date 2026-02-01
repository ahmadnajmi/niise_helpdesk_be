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
                    ->with('childCategoryRecursive')
                    ->when(request()->branch_id, function ($query) {
                        return $query->whereHas('sla', function ($query) {
                            $query->whereRaw("JSON_EXISTS(branch_id, '\$[*]?(@ == " . request()->branch_id . ")')");
                        });
                    });
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

    public static function buildCategoryHierarchy($categories){
        $hierarchy = collect();
        $childrenMap = $categories->groupBy('category_id');

        foreach ($categories as $category) {
            $descendants = collect([$category->id]);
            $toProcess = collect([$category->id]);

            while ($toProcess->isNotEmpty()) {
                $currentId = $toProcess->shift();
                $children = $childrenMap->get($currentId, collect());
                
                foreach ($children as $child) {
                    if (!$descendants->contains($child->id)) {
                        $descendants->push($child->id);
                        $toProcess->push($child->id);
                    }
                }
            }

            $hierarchy->put($category->id, $descendants);
        }

        return $hierarchy;
    }
}


