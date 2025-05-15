<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OperatingTimeResources extends JsonResource
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
            'day' => $this->day,
            'day_desc' => $this->dayDescription?->name,
            'duration' => $this->duration,
            'duration_desc' => $this->durationDescription?->name,
            'operation_start' => $this->operation_start,
            'operation_end' => $this->operation_end,
            'is_active' => $this->is_active,
            'created_by' => $this->createdBy->name .' - '. $this->createdBy->email ,
            'updated_by' => $this->updatedBy->name .' - '. $this->updatedBy->email ,
            'created_at' => $this->created_at->format('d-m-Y'),
            'updated_at' => $this->updated_at->format('d-m-Y'),
            
        ];
    }
}
