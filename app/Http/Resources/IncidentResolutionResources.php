<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class IncidentResolutionResources extends JsonResource
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
            'action_codes'=> $this->action_codes,
            'action_codes_details' => new ActionCodeResources($this->actionCodes),
            'group_id'=> $this->group_id,
            'operation_user_id'=> $this->operation_user_id,
            'report_contractor_no'=> $this->report_contractor_no,
            'solution_notes'=> $this->solution_notes,
            'notes'=> $this->notes,
            'created_by' => $this->createdBy->name .' - '. $this->createdBy->email ,
            'updated_by' => $this->updatedBy->name .' - '. $this->updatedBy->email ,
            'created_at' => $this->created_at->format('d-m-Y H:i:s'),
            'updated_at' => $this->updated_at->format('d-m-Y H:i:s'),
        ];
    }
}
