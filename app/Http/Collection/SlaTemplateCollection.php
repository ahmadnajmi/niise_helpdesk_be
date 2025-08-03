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

                'response_time' => $query->response_time,
                'response_time_type' => $query->response_time_type,
                'response_time_type_desc' => $query->responseTimeTypeDescription?->name,
                'response_time_penalty' => $query->response_time_penalty,

                'resolution_time' => $query->resolution_time,
                'resolution_time_type' => $query->resolution_time_type,
                'resolution_time_type_desc' => $query->resolutionTimeTypeDescription?->name,
                'resolution_time_penalty' => $query->resolution_time_penalty,

                'response_time_location' => $query->response_time_location,
                'response_time_location_type' => $query->response_time_location_type,
                'response_time_location_type_desc' => $query->responseTimeLocationTypeDescription?->name,
                'response_time_location_penalty' => $query->response_time_location_penalty,

                'temporary_resolution_time' => $query->temporary_resolution_time,
                'temporary_resolution_time_type' => $query->temporary_resolution_time_type,
                'temporary_resolution_time_type_desc' => $query->temporaryResolutionTimeTypeDescription?->name,
                'temporary_resolution_time_penalty' => $query->temporary_resolution_time_penalty,

                'dispatch_time' => $query->dispatch_time,
                'dispatch_time_type' => $query->dispatch_time_type,
                'dispatch_time_type_desc' => $query->dispatchTimeTypeDescription?->name,

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
