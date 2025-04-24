<?php

namespace App\Http\Collection;

use Illuminate\Http\Request;
use App\Http\Resources\ModuleResources;
use App\Http\Resources\PermissionResources;

class CategoryCollection extends BaseResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request)
    {
        return $this->collection->transform(function ($query) use($request){
            $return =  [
                'id' => $query->id,
                'name' => $query->name,
                'level' => $query->level,
                'code' => $query->code,
                'description' => $query->description,
                'is_active' => $query->is_active,
                'category_id' => $query->category_id,
                'created_by' => $query->createdBy->name .' - '. $query->createdBy->email ,
                'updated_by' => $query->updatedBy->name .' - '. $query->updatedBy->email ,
            ];
            return $return;
        });
    }
}


