<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PermisssionCollection extends JsonResource
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
            'role_id' => $this->role_id,
            'sub_module_id' => $this->sub_module_id,
            'sub_module_name' => $this->subModule->name,
            'allowed_list' => $this->allowed_list,
            'allowed_create' => $this->allowed_create,
            'allowed_view' => $this->allowed_view,
            'allowed_update' => $this->allowed_update,
            'allowed_delete' => $this->allowed_delete,
            'created_at' => $this->created_at->format('d-m-Y'),
            'updated_at' => $this->updated_at->format('d-m-Y'),
        ];
    }
}
