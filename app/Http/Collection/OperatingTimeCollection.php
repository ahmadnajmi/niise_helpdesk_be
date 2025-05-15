<?php

namespace App\Http\Collection;

use Illuminate\Http\Request;

class OperatingTimeCollection extends BaseResource
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
                'day' => $query->day,
                'day_desc' => $query->dayDescription?->name,
                'duration' => $query->duration,
                'duration_desc' => $query->durationDescription?->name,
                'operation_start' => $query->operation_start,
                'operation_end' => $query->operation_end,
                'is_active' => $query->is_active,
                'created_by' => $query->createdBy->name .' - '. $query->createdBy->email ,
                'updated_by' => $query->updatedBy->name .' - '. $query->updatedBy->email ,
                'created_at' => $query->created_at->format('d-m-Y'),
                'updated_at' => $query->updated_at->format('d-m-Y'),
            ];
            return $return;
        });
    }
}
