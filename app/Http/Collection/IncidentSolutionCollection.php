<?php

namespace App\Http\Collection;

use Illuminate\Http\Request;
use App\Http\Resources\ActionCodeResources;

class IncidentSolutionCollection extends BaseResource
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
                'action_codes'=> $query->action_codes,
                'action_codes_details' => new ActionCodeResources($query->actionCodes),
                'solution_notes'=> $query->solution_notes,
                'created_at' => $query->created_at->format('d-m-Y H:i:s'),
                'updated_at' => $query->updated_at->format('d-m-Y H:i:s'),
                'created_by' => $query->createdBy->name .' - '. $query->createdBy->email ,
                'updated_by' => $query->updatedBy->name .' - '. $query->updatedBy->email ,
            ];
            return $return;
        });
    }
}
