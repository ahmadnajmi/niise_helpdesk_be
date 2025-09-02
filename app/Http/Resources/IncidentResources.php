<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Collection\IncidentResolutionCollection;
use App\Http\Services\AssetServices;

class IncidentResources extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $asset_information = [];

        $asset_id = $this->asset_parent_id ? [$this->asset_parent_id] : json_decode($this->asset_component_id);

        $asset_id = $asset_id ? $asset_id : [];

        if(count($asset_id) > 0){
            $asset_service = new AssetServices();

            $asset_information =  $asset_service->getAsset($asset_id);
        }

        return [
            'id' => $this->id,
            'code_sla' => $this->code_sla,
            'sla_details'=> $this->sla ? new SlaResources($this->sla) : null,
            'incident_no' =>  $this->incident_no,
            // 'incident_date' => $this->incident_date?->format('d-m-Y'),
            'barcode' => $this->barcode,
            'branch_id' => $this->branch_id,
            'branch_details' => $this->branch,
            'category_id' => $this->category_id,
            'category_details' => new CategoryResources($this->categoryDescription),
            'complaint_id' => $this->complaint_id,
            'information' => $this->information,
            'knowledge_base_id' => $this->knowledge_base_id,
            'received_via' => $this->received_via,
            'received_via_desc' => $this->receviedViaDescription?->name,
            'asset_parent_id' => $this->asset_parent_id,
            'asset_component_id' => $this->asset_component_id,
            'asset_information' => $asset_information,
            'report_no' => $this->report_no,
            'incident_asset_type' => $this->incident_asset_type,
            'date_asset_loss' => $this->date_asset_loss?->format('d-m-Y'),
            'date_report_police' => $this->date_report_police?->format('d-m-Y'),
            'report_police_no' => $this->report_police_no,
            'asset_siri_no' => $this->asset_siri_no,
            'group_id' => $this->group_id,
            'group_details' => new GroupResources($this->group),
            'operation_user_id' => $this->operation_user_id,
            'operation_user_details' => new UserResources($this->operationUser),
            'asset_file' => $this->asset_file,
            'appendix_file' => $this->appendix_file,
            'service_recipient_id' => $this->service_recipient_id,
            'service_recipient_details' => new UserResources($this->serviceRecipient),
            'incident_solution' => new IncidentResolutionCollection($this->incidentResolution),
            'complainant' => new ComplaintResources($this->complaint) ,
            'incident_date' => $this->incident_date?->format('d-m-Y H:i:s'),
            'expected_end_date' => $this->expected_end_date?->format('d-m-Y H:i:s'),
            'actual_end_date' => $this->actual_end_date?->format('d-m-Y H:i:s'),
            'status' => $this->status,
            'status_desc' => $this->statusDesc?->name,
            'countdown_settlement_date' => $this->calculateCountDownSettlement,
            'breach_time' => $this->calculateBreachTime,
            'created_by' => $this->createdBy->name .' - '. $this->createdBy->email ,
            'updated_by' => $this->updatedBy->name .' - '. $this->updatedBy->email ,
            'created_at' => $this->created_at->format('d-m-Y H:i:s'),
            'updated_at' => $this->updated_at->format('d-m-Y H:i:s'),
        ];
    }
}
