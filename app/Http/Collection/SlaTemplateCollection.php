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
            $lang = substr(request()->header('Accept-Language'), 0, 2);

            $return =  [
                'id' => $query->id,
                'code' => $query->code,
                'severity_id' => $query->severity_id,
                'severity_desc' => $lang === 'en' ? $query->severityDescription?->name_en  : $query->severityDescription?->name,
                'service_level' => $query->service_level,
                'timeframe_channeling' => $query->timeframe_channeling,
                'timeframe_channeling_type' => $query->timeframe_channeling_type,
                'timeframe_channeling_type_desc' => $lang === 'en' ? $query->channelingTypeDescription?->name_en  : $query->channelingTypeDescription?->name,
                'timeframe_incident' => $query->timeframe_incident,
                'timeframe_incident_type' => $query->timeframe_incident_type,
                'timeframe_incident_type_desc' => $lang === 'en' ? $query->incidentTypeDescription?->name_en  : $query->incidentTypeDescription?->name,
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
                'created_at' => $query->created_at->format('d-m-Y'),
                'updated_at' => $query->updated_at->format('d-m-Y'),
            ];
            return $return;
        });
    }
}
