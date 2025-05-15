<?php

namespace App\Http\Collection;

use Illuminate\Http\Request;

class EmailTemplateCollection extends BaseResource
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
                'sender_name' => $query->sender_name,
                'sender_email' => $query->sender_email,
                'notes' => $query->notes,
                'created_by' => $query->createdBy->name .' - '. $query->createdBy->email ,
                'updated_by' => $query->updatedBy->name .' - '. $query->updatedBy->email ,
                'created_at' => $query->created_at->format('d-m-Y'),
                'updated_at' => $query->updated_at->format('d-m-Y'),
            ];
            return $return;
        });
    }
}