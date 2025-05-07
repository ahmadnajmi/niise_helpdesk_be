<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResources extends JsonResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        $return  = [
            'id' => $this->id,
            'ic_no' => $this->ic_no,
            'name' => $this->name,
            'nickname'  =>$this->nickname,
            'position' => $this->position,
            'branch' => $this->branch,
            'email' => $this->email,
            'phone_no' => $this->phone_no,
            'is_active' => $this->is_active,
            'created_at' => $this->created_at?->format('d-m-Y'),
            'updated_at' => $this->updated_at?->format('d-m-Y'),
        ];
        return $return;
    }
}
