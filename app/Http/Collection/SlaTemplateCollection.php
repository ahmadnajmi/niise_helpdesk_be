<?php

namespace App\Http\Collection;

use Illuminate\Http\Request;

class SlaTemplateCollection extends BaseResource
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
                'severity_id' => $query->severity_id,
                'severity_desc' => $query->severityDescription?->name,
                'service_level' => $query->service_level,
                'timeframe_channeling' => $query->timeframe_channeling,
                'timeframe_channeling_type' => $query->timeframe_channeling_type,
                'timeframe_channeling_type_desc' => $query->channelingTypeDescription?->name,
                'timeframe_incident' => $query->timeframe_incident,
                'timeframe_incident_type' => $query->timeframe_incident_type,
                'timeframe_incident_type_desc' => $query->incidentTypeDescription?->name,
                'response_time_reply' => $query->response_time_reply,
                'response_time_reply_type' => $query->response_time_reply_type,
                'response_time_reply_type_desc' => $query->replyTypeDescription?->name,
                'timeframe_solution' => $query->timeframe_solution,
                'timeframe_solution_type' => $query->timeframe_solution_type,
                'timeframe_solution_type_desc' => $query->solutionTypeDescription?->name,
                'response_time_location' => $query->response_time_location,
                'response_time_location_type' => $query->response_time_location_type,
                'response_time_location_type_desc' => $query->locationTypeDescription?->name,
                'notes' => $query->notes,
                'created_by' => $query->createdBy->name .' - '. $query->createdBy->email ,
                'updated_by' => $query->updatedBy->name .' - '. $query->updatedBy->email ,
            ];
            return $return;
        });
    }
}
