<?php

namespace App\Http\Collection;

use Illuminate\Http\Request;

class CompanyCollection extends BaseResource
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
                'nickname'  =>$query->nickname,
                'email' => $query->email,
                'phone_no' => $query->phone_no,
                'address' => $query->address,
                'postcode' => $query->postcode,
                'city' => $query->city,
                'state_id' => $query->state_id,
                'state_desc' => $query->stateDescription?->name,
                'fax_no' => $query->fax_no,
                'is_active' => $query->is_active,
                'created_by' => $query->createdBy->name .' - '. $query->createdBy->email ,
                'updated_by' => $query->updatedBy->name .' - '. $query->updatedBy->email ,
                'created_by' => $query->createdBy->name .' - '. $query->createdBy->email ,
                'updated_by' => $query->updatedBy->name .' - '. $query->updatedBy->email ,
            ];
            return $return;
        });
    }
}
