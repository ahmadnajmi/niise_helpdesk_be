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
            'created_at' => $this->created_at->format('d-m-Y'),
            'updated_at' => $this->updated_at->format('d-m-Y'),
        ];
    }
}
