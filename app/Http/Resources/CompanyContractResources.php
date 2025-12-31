<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CompanyContractResources extends JsonResource
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
            'name'=> $this->name,
            'start_date'=> $this->start_date?->format('Y-m-d'),
            'end_date'=> $this->end_date?->format('Y-m-d'),
            'company_id'=> $this->company_id,
            'contract_no'=> $this->contract_no,
            'is_active'=> $this->is_active,
            'created_by' => $this->createdBy?->name .' - '. $this->createdBy?->email ,
            'updated_by' => $this->updatedBy?->name .' - '. $this->updatedBy?->email ,
            'created_at' => $this->created_at->format('d-m-Y'),
            'updated_at' => $this->updated_at->format('d-m-Y'),
        ];
    }
}
