<?php

namespace App\Http\Collection;

use Illuminate\Http\Request;

class IncidentCollection extends BaseResource
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
                'incident_no' =>  $query->incident_no,
                'information' => $query->information,
                'end_date' => $query->end_date?->format('d-m-Y'),
                'phone_no' => $query->complaint->phone_no,
                'severity' => $query->sla?->slaTemplate?->severityDescription?->name,
                'incident_solution' => new IncidentSolutionCollection($query->incidentSolution),
                'created_at' => $query->created_at->format('d-m-Y'),
                'updated_at' => $query->updated_at->format('d-m-Y'),
                'created_by' => $query->createdBy->name .' - '. $query->createdBy->email ,
                'updated_by' => $query->updatedBy->name .' - '. $query->updatedBy->email ,
            ];
            return $return;
        });
    }
}
