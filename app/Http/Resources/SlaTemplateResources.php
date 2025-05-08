<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SlaTemplateResources extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
         return [
            'id' => $this->id,
            'severity_id' => $this->severity_id,
            'severity_desc' => $this->severityDescription?->name,
            'service_level' => $this->service_level,
            'timeframe_channeling' => $this->timeframe_channeling,
            'timeframe_channeling_type' => $this->timeframe_channeling_type,
            'timeframe_channeling_type_desc' => $this->channelingTypeDescription?->name,
            'timeframe_incident' => $this->timeframe_incident,
            'timeframe_incident_type' => $this->timeframe_incident_type,
            'timeframe_incident_type_desc' => $this->incidentTypeDescription?->name,
            'response_time_reply' => $this->response_time_reply,
            'response_time_reply_type' => $this->response_time_reply_type,
            'response_time_reply_type_desc' => $this->replyTypeDescription?->name,
            'timeframe_solution' => $this->timeframe_solution,
            'timeframe_solution_type' => $this->timeframe_solution_type,
            'timeframe_solution_type_desc' => $this->solutionTypeDescription?->name,
            'response_time_location' => $this->response_time_location,
            'response_time_location_type' => $this->response_time_location_type,
            'response_time_location_type_desc' => $this->locationTypeDescription?->name,
            'notes' => $this->notes,
            'created_at' => $this->created_at->format('d-m-Y'),
            'updated_at' => $this->updated_at->format('d-m-Y'),
        ];
    }
}
