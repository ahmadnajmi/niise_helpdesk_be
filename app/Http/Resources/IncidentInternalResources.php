<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;
use App\Http\Services\IncidentServices;
use App\Http\Collection\OperatingTimeCollection;
use App\Models\Calendar;

class IncidentInternalResources extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $generate_penalty  = IncidentServices::checkPenalty($this->resource);
        $generate_due_date = IncidentServices::calculateDueDateIncident($this->resource);
        $get_public_holiday = Calendar::getPublicHoliday($this->branch?->state_id, $this->incident_date->format('Y'));

        return [
            'id' => $this->id,
            'incident_no' =>  $this->incident_no,
            'branch_name' => $this->branch?->name,
            'state_name' => $this->branch?->stateDescription?->name,  
            'incident_date' => $this->incident_date?->format('d-m-Y H:i:s'),
            'expected_end_date' => $this->expected_end_date?->format('d-m-Y H:i:s'),
            'actual_end_date' => $this->actual_end_date?->format('d-m-Y H:i:s'),   
            'sla_version' => $this->slaVersion->version,      
            'sla_template_code' => $this->slaVersion?->slaTemplate?->code,      
            'sla_code' => $this->sla?->code,
            'penalty_irt' => $this->incidentPenalty?->penalty_irt,
            'penalty_ort' => $this->incidentPenalty?->penalty_ort,
            'penalty_prt' => $this->incidentPenalty?->penalty_prt,
            'penalty_vprt' => $this->incidentPenalty?->penalty_vprt,
            'irt_time' => $this->slaVersion?->response_time,      
            'irt_time_type' => $this->slaVersion?->responseTimeTypeDescription?->name,      
            'irt_penalty' => $this->slaVersion?->response_time_penalty,      
            'irt_penalty_type' => $this->slaVersion?->responseTimePenaltyTypeDescription?->name,      
            'prt_time' => $this->slaVersion?->resolution_time,      
            'prt_time_type' => $this->slaVersion?->resolutionTimeTypeDescription?->name,      
            'prt_penalty' => $this->slaVersion?->resolution_time_penalty,      
            'prt_penalty_type' => $this->slaVersion?->resolutionTimePenaltyTypeDescription?->name,      
            'ort_time' => $this->slaVersion?->response_time_location,      
            'ort_time_type' => $this->slaVersion?->responseTimeLocationTypeDescription?->name,      
            'ort_penalty' => $this->slaVersion?->response_time_location_penalty,      
            'ort_penalty_type' => $this->slaVersion?->responseTimeLocationPenaltyTypeDescription?->name,      
            'vprt_time' => $this->slaVersion?->verify_resolution_time,      
            'vprt_time_type' => $this->slaVersion?->verifyResolutionTimeTypeDescription?->name,      
            'vprt_penalty' => $this->slaVersion?->verify_resolution_time_penalty,      
            'vprt_penalty_type' => $this->slaVersion?->verifyResolutionTimePenaltyTypeDescription?->name, 
            'stimulate_expected_end_date' => $generate_due_date->format('d-m-Y H:i:s'),
            "stimulate_penalty_irt" => $generate_penalty['penalty_irt'],
            "stimulate_penalty_ort" => $generate_penalty['penalty_ort'],
            "stimulate_penalty_prt" => $generate_penalty['penalty_prt'],
            "stimulate_penalty_vprt" => $generate_penalty['penalty_vprt'],
            'operating_times' =>  new OperatingTimeCollection($this->branch?->operatingTime),  
            'public_holiday' => $get_public_holiday,
            'created_by' => $this->createdBy?->name .' - '. $this->createdBy?->email ,
            'updated_by' => $this->updatedBy?->name .' - '. $this->updatedBy?->email ,
            'created_at' => $this->created_at->format('d-m-Y H:i:s'),
            'updated_at' => $this->updated_at->format('d-m-Y H:i:s'),
        ];
    }
}
