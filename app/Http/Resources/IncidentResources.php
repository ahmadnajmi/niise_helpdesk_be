<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Collection\IncidentSolutionCollection;

class IncidentResources extends JsonResource
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
            'code_sla' => $this->code_sla,
            'incident_no' =>  $this->incident_no,
            'incident_date' => $this->incident_date?->format('d-m-Y'),
            'branch_id' => $this->branch_id,
            'category_id' => $this->category_id,
            'complaint_id' => $this->complaint_id,
            'information' => $this->information,
            'knowledge_base_id' => $this->knowledge_base_id,
            'received_via' => $this->received_via,
            'report_no' => $this->report_no,
            'incident_asset_type' => $this->incident_asset_type,
            'date_asset_loss' => $this->date_asset_loss?->format('d-m-Y'),
            'date_report_police' => $this->date_report_police?->format('d-m-Y'),
            'report_police_no' => $this->report_police_no,
            'asset_siri_no' => $this->asset_siri_no,
            'group_id' => $this->group_id,
            'operation_user_id' => $this->operation_user_id,
            'appendix_file' => $this->appendix_file,
            'incident_solution' => new IncidentSolutionCollection($this->incidentSolution),
            'complainant' => new ComplaintResources($this->complaint) ,
            'created_by' => $this->createdBy->name .' - '. $this->createdBy->email ,
            'updated_by' => $this->updatedBy->name .' - '. $this->updatedBy->email ,
            'created_at' => $this->created_at->format('d-m-Y'),
            'updated_at' => $this->updated_at->format('d-m-Y'),
        ];
    }
}
