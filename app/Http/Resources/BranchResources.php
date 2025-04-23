<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BranchResources extends JsonResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'branch_code' => $this->id,
            'name' => $this->name,
            'category' => $this->category,
            'state' => $this->state,
            'location' => $this->location,
        ];
    }
}
