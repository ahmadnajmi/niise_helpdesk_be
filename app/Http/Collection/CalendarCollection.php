<?php

namespace App\Http\Collection;

use Illuminate\Http\Request;
use App\Http\Resources\ModuleResources;
use App\Http\Resources\PermissionResources;

class CalendarCollection extends BaseResource
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
                'start_date' => $query->start_date,
                'end_date' => $query->end_date,
                'state_id' => $query->state_id,
                'state_desc' => $query->state_id == 0 ? 'Semua Negeri' : $query->stateDescription?->name,
                'description' => $query->description,
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

