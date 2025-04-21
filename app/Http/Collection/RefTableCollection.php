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
                'received_by' => $query->received_by,
                'received_by_description' => $query->received_by_description,
                'branch_type' => $query->branch_type,
                'branch_type_description' => $query->branch_type_description,
                'is_active' => $query->is_active,
                'created_by' => $query->createdBy->name,
                'updated_by' => $query->updatedBy->name,
                'created_at' => $query->created_at->format('d-m-Y'),
                'updated_at' => $query->updated_at->format('d-m-Y'),
            ];
        });
    }
}
