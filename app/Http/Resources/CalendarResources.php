<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CalendarResources extends JsonResource
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
            'name' => $this->name,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'state_id' => $this->state_id,
            'state_desc' => $this->stateDescription?->name,
            'description' => $this->description,
            'is_active' => $this->is_active,
            'created_by' => $this->createdBy->name .' - '. $this->createdBy->email ,
            'updated_by' => $this->updatedBy->name .' - '. $this->updatedBy->email ,
        ];
    }
}
