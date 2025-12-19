<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EmailTemplateResources extends JsonResource
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
            'sender_name' => $this->sender_name,
            'sender_email' => $this->sender_email,
            'notes' => $this->notes,
            'is_active'=> $this->is_active,
            'created_by' => $this->createdBy?->name .' - '. $this->createdBy?->email ,
            'updated_by' => $this->updatedBy?->name .' - '. $this->updatedBy?->email ,
            'created_at' => $this->created_at->format('d-m-Y'),
            'updated_at' => $this->updated_at->format('d-m-Y'),
        ];
    }
}
