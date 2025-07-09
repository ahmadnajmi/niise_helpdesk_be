<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RefTableResources extends JsonResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'code_category' => $this->code_category,
            'ref_code' => $this->ref_code,
            'name_en' => $this->name_en,
            'name' => $this->name,
            'ref_code_parent' => $this->ref_code_parent,
            'ref_code_parent_desc' => $this->ref_code_parent ? $this->getParentDesc('severity',$this->ref_code_parent) : null,
            'created_by' => $this->createdBy->name .' - '. $this->createdBy->email ,
            'updated_by' => $this->updatedBy->name .' - '. $this->updatedBy->email ,
            'created_at' => $this->created_at->format('d-m-Y'),
            'updated_at' => $this->updated_at->format('d-m-Y'),
        ];
    }
}
