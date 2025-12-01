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
            'code' => $this->code,
            'severity_id' => $this->severity_id,
            'severity_desc' => $this->severityDescription?->name,
            'service_level' => $this->service_level,

            'company_id' => $this->company_id,
            'company_name' => $this->company?->name,

            'company_contract_id' => $this->company_contract_id,
            'company_contract_name' => $this->companyContract?->name,
            'company_contract_details' => $this->companyContract ? new CompanyContractResources($this->companyContract) : null,

            'response_time' => $this->response_time,
            'response_time_type' => $this->response_time_type,
            'response_time_type_desc' => $this->responseTimeTypeDescription?->name,
            'response_time_penalty' => $this->response_time_penalty,
            'response_time_penalty_type' => $this->response_time_penalty_type,
            'response_time_penalty_type_desc' => $this->responseTimePenaltyTypeDescription?->name,

            'resolution_time' => $this->resolution_time,
            'resolution_time_type' => $this->resolution_time_type,
            'resolution_time_type_desc' => $this->resolutionTimeTypeDescription?->name,
            'resolution_time_penalty' => $this->resolution_time_penalty,
            'resolution_time_penalty_type' => $this->resolution_time_penalty_type,
            'resolution_time_penalty_type_desc' => $this->resolutionTimePenaltyTypeDescription?->name,

            'response_time_location' => $this->response_time_location,
            'response_time_location_type' => $this->response_time_location_type,
            'response_time_location_type_desc' => $this->responseTimeLocationTypeDescription?->name,
            'response_time_location_penalty' => $this->response_time_location_penalty,
            'response_time_location_penalty_type' => $this->response_time_location_penalty_type,
            'response_time_location_penalty_type_desc' => $this->responseTimeLocationPenaltyTypeDescription?->name,

            'temporary_resolution_time' => $this->temporary_resolution_time,
            'temporary_resolution_time_type' => $this->temporary_resolution_time_type,
            'temporary_resolution_time_type_desc' => $this->temporaryResolutionTimeTypeDescription?->name,
            'temporary_resolution_time_penalty' => $this->temporary_resolution_time_penalty,
            'temporary_resolution_time_penalty_type' => $this->temporary_resolution_time_penalty_type,
            'temporary_resolution_time_penalty_type_desc' => $this->temporaryResolutionTimePenaltyTypeDescription?->name,

            'dispatch_time' => $this->dispatch_time,
            'dispatch_time_type' => $this->dispatch_time_type,
            'dispatch_time_type_desc' => $this->dispatchTimeTypeDescription?->name,

            'notes' => $this->notes,
            'created_by' => $this->createdBy?->name .' - '. $this->createdBy?->email ,
            'updated_by' => $this->updatedBy?->name .' - '. $this->updatedBy?->email ,
            'created_at' => $this->created_at->format('d-m-Y'),
            'updated_at' => $this->updated_at->format('d-m-Y'),
            
        ];
    }
}
