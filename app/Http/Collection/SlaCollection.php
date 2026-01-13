<?php

namespace App\Http\Collection;

use Illuminate\Http\Request;
use App\Http\Resources\SlaTemplateResources;

class SlaCollection extends BaseResource
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
                'code' => $query->code,
                'category_id' => $query->category_id,
                'category_desc' => $query->category?->name,
                'branch_id'=> json_decode($query->branch_id),
                'branch_name' => $query->getBranchDesc($query->branch_id),
                'branch_details' => $query->getBranchDetails($query->branch_id),
                'sla_template_id'=> $query->sla_template_id,
                'sla_template_details' => $query->slaTemplate ? new SlaTemplateResources($query->slaTemplate) : null,
                'group_id'=> $query->group_id,
                'group_name' => $query->group?->name,
                'is_active' => $query->is_active == 1 ? true : false,
                'created_by' => $query->createdBy?->name .' - '. $query->createdBy?->email ,
                'updated_by' => $query->updatedBy?->name .' - '. $query->updatedBy?->email ,
                'created_at' => $query->created_at->format('d-m-Y'),
                'updated_at' => $query->updated_at->format('d-m-Y'),
            ];
            return $return;
        });
    }
}
