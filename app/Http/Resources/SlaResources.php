<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SlaResources extends JsonResource
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
            'category_id' => $this->category_id,
            'category_desc' => $this->category->name,
            'code' => $this->code,
            'state_id'=> $this->state_id,
            'state_desc' => $this->stateDescription?->name,
            'branch_id'=> $this->branch_id,
            'branch_name' => $this->branch?->name,
            'start_date'=> $this->start_date->format('Y-m-d'),
            'end_date'=> $this->end_date->format('Y-m-d'),
            'sla_template_id'=> $this->sla_template_id,
            'sla_template_details' => $this->slaTemplate ? new SlaTemplateResources($this->slaTemplate) : null,
            'group_id'=> $this->group_id,
            'group_name' => $this->group?->name,
            'is_active' => $this->is_active == 1,
            'loaner' => $this->loaner,
            'penalty' => $this->penalty,
            'created_at' => $this->created_at->format('d-m-Y'),
            'updated_at' => $this->updated_at->format('d-m-Y'),
            'created_by' => $this->createdBy->name .' - '. $this->createdBy->email ,
            'updated_by' => $this->updatedBy->name .' - '. $this->updatedBy->email ,
        ];
    }
}
