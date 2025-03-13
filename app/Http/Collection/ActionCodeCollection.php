<?php

namespace App\Http\Collection;

use Illuminate\Http\Request;

class ActionCodeCollection extends BaseResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request)
    {
        return $this->collection->transform(function ($query) use($request){
            return [
                'id' => $query->id,
                'name' => $query->name,
                'category' => $query->category,
                'category_desc' => $query->categoryDescription->name,
                'abbreviation' => $query->abbreviation,
                'description' => $query->description,
                'is_active' => $query->is_active,
                'created_by' => $query->createdBy->name,
                'updated_by' => $query->updatedBy->name,
                'created_at' => $query->created_at->format('d-m-Y'),
                'updated_at' => $query->updated_at->format('d-m-Y'),
            ];
        });
    }
}
