<?php

namespace App\Http\Collection;

use Illuminate\Http\Request;

class RefTableCollection extends BaseResource
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
                'code_category' => $query->code_category,
                'ref_code' => $query->ref_code,
                'name_en' => $query->name_en,
                'name' => $query->name,
                'ref_code_parent' => $query->ref_code_parent,
                'created_by' => $query->createdBy->name .' - '. $query->createdBy->email ,
                'updated_by' => $query->updatedBy->name .' - '. $query->updatedBy->email ,
                'created_at' => $query->created_at->format('d-m-Y'),
                'updated_at' => $query->updated_at->format('d-m-Y'),
            ];
        });
    }
}
