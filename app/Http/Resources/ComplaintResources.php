<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ComplaintResources extends JsonResource
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
            'name'  =>$this->name,
            'email' => $this->email,
            'phone_no' => $this->phone_no,
            'office_phone_no' => $this->office_phone_no,
            'extension_no' => $this->extension_no,
            'address' => $this->address,
            'postcode' => $this->postcode,
            'state_id' => $this->state_id,
            'state_desc' => $this->stateDescription?->name,
            'created_by' => $this->createdBy?->name .' - '. $this->createdBy?->email ,
            'updated_by' => $this->updatedBy?->name .' - '. $this->updatedBy?->email ,
            'created_at' => $this->created_at->format('d-m-Y'),
            'updated_at' => $this->updated_at->format('d-m-Y'),
        ];
    }
}
