<?php

namespace App\Http\Collection;

use Illuminate\Http\Request;

class CompanyContractCollection extends BaseResource
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
                'name'=> $query->name,
                'start_date'=> $query->start_date?->format('Y-m-d'),
                'end_date'=> $query->end_date?->format('Y-m-d'),
                'company_id'=> $query->company_id,
                'is_active'=> $query->is_active,
                'created_at' => $query->created_at->format('d-m-Y'),
                'updated_at' => $query->updated_at->format('d-m-Y'),
                'created_by' => $query->createdBy->name .' - '. $query->createdBy->email ,
                'updated_by' => $query->updatedBy->name .' - '. $query->updatedBy->email ,
            ];
            return $return;
        });
    }
}
