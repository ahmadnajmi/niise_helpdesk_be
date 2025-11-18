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
                'company_id' => $query->company_id,
                'company_name' => $query->company?->name,
                'company_contract_id' => $query->company_contract_id,
                'company_contract_name' => $query->companyContract?->name,
                'created_by' => $query->createdBy?->name .' - '. $query->createdBy?->email ,
                'updated_by' => $query->updatedBy?->name .' - '. $query->updatedBy?->email ,
                'created_at' => $query->created_at->format('d-m-Y'),
                'updated_at' => $query->updated_at->format('d-m-Y'),
            ];
            return $return;
        });
    }
}
