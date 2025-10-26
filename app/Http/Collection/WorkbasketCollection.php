<?php

namespace App\Http\Collection;

use Illuminate\Http\Request;
use App\Http\Resources\CategoryResources;
use App\Http\Resources\SlaTemplateResources;
use App\Http\Resources\GroupResources;


class WorkbasketCollection extends BaseResource
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
                'incident_id' => $query->incident_id,
                'incident_no' => $query->incident?->incident_no,
                'complaint_user_id' => $query->incident?->complaint_user_id,
                'date' => $query->date->format('d-m-Y H:i:s'),
                'category_details' => $query->incident?->categoryDescription ? new CategoryResources($query->incident->categoryDescription) : null,
                'sla_template_details' => $query->incident?->sla?->slaTemplate ? new SlaTemplateResources($query->incident->sla->slaTemplate) : null,
                'information' => $query->incident?->information,
                'group_details' =>  $query->incident?->group ? new GroupResources($query->incident->group) : null,
                'handle_by' => $query->handle_by,
                'status' => $query->status,
                'status_desc' => $query->statusDesc?->translated_name,
                'status_complaint' => $query->status,
                'status_complaint_desc' => $query->statusComplaintDesc?->translated_name,
                'incident_status' => $query->incident?->status,
                'incident_status_desc' => $query->incident?->statusDesc?->translated_name,
                'created_at' => $query->created_at->format('d-m-Y'),
                'updated_at' => $query->updated_at->format('d-m-Y'),
            ];
            return $return;
        });
    }
}
