<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ModuleCollection extends JsonResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        $return =  [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'is_active' => $this->is_active,
        ];

        if($request->route()->getName() == 'role.show'){
            $return['permissions'] = PermisssionCollection::collection($this->permissions);
            $return['sub_modules'] = ModuleCollection::collection($this->submodule);
        }

        return $return;
    }
}
