<?php

namespace App\Http\Collection;

use Illuminate\Http\Request;
use App\Http\Resources\GroupResources;
use App\Http\Resources\UserResources;
use App\Http\Resources\ComplaintResources;
use App\Http\Resources\CategoryResources;
use App\Http\Resources\SlaResources;

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
                'code_sla' => $query->code_sla,
                'sla_details'=> $query->sla ? new SlaResources($query->sla) : null,
                'incident_no' =>  $query->incident_no,
                'incident_date' => $query->incident_date?->format('d-m-Y'),
                'branch_id' => $query->branch_id,
                'branch_details' => $query->branch,
                'category_id' => $query->category_id,
                'category_details' => $query->categoryDescription ? new CategoryResources($query->categoryDescription) : null,
                'complaint_id' => $query->complaint_id,
                'information' => $query->information,
                'knowledge_base_id' => $query->knowledge_base_id,
                'received_via' => $query->received_via,
                'received_via_desc' => $query->receviedViaDescription?->name,

                'report_no' => $query->report_no,
                'incident_asset_type' => $query->incident_asset_type,
                'date_asset_loss' => $query->date_asset_loss?->format('d-m-Y'),
                'date_report_police' => $query->date_report_police?->format('d-m-Y'),
                'report_police_no' => $query->report_police_no,
                'asset_siri_no' => $query->asset_siri_no,
                'group_id' => $query->group_id,
                'group_details' => new GroupResources($query->group),
                'operation_user_id' => $query->operation_user_id,
                'operation_user_details' => new UserResources($query->operationUser),

                'appendix_file' => $query->appendix_file,
                'incident_solution' => new IncidentSolutionCollection($query->incidentSolution),
                'complainant' => new ComplaintResources($query->complaint) ,
                'created_at' => $query->created_at->format('d-m-Y'),
                'updated_at' => $query->updated_at->format('d-m-Y'),
                'created_by' => $query->createdBy->name .' - '. $query->createdBy->email ,
                'updated_by' => $query->updatedBy->name .' - '. $query->updatedBy->email ,
            ];
            return $return;
        });
    }
}
