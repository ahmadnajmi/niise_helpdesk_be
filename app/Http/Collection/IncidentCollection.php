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
                'incident_no' =>  $query->incident_no,
                'incident_date' => $query->incident_date?->format('d-m-Y H:i:s'),
                'branch' => $query->branch?->name,
                'information' => $query->information,
                'severity' => $query->sla?->slaTemplate?->severityDescription?->name,
                'phone_no' =>$query->complaintUser?->phone_no,
                'actual_end_date' => $query->actual_end_date?->format('d-m-Y H:i:s'),
                'status_desc' => $query->statusDesc?->name,
                'status' => $query->status,
                'created_at' => $query->created_at?->format('d-m-Y'),
                'updated_at' => $query->updated_at?->format('d-m-Y'),
                'created_by' => $query->createdBy?->name .' - '. $query->createdBy?->email ,
                'updated_by' => $query->updatedBy?->name .' - '. $query->updatedBy?->email ,
            ];
            return $return;
        });
    }
}
