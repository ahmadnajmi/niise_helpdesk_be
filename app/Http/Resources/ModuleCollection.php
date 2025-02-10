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
            'name' => $this->translated_name,
            'description' => $this->description,
            'is_active' => $this->is_active,
        ];

        if($request->route()->getName() == 'module.index'){
            $return['total_permissions'] = $this->permissions->count()  + $this->getTotalSubModuleCountAttribute();
            $return['total_roles_can_access'] = $this->roles->count();
            $return['total_users_can_access'] = rand(5,10);
            $return['sub_modules'] = $this->submodule->pluck('name');
            $return['permissions'] = PermissionCollection::collection($this->permissions);

        }
        elseif($request->route()->getName() == 'role.show' || $request->route()->getName() == 'module.show'){
            $return['sub_modules'] = ModuleCollection::collection($this->submodule);
        }
        elseif($request->route()->getName() == 'role.show'){
            $return['permissions'] = PermissionCollection::collection($this->permissions->where('name','index')->first());
            $return['sub_modules'] = ModuleCollection::collection($this->submodule);
        }
        if($request->route()->getName() == 'navigation.index'){
            $return['permissions'] = new PermissionCollection($this->permissions->where('name','index')->first());
            $return['sub_modules'] = ModuleCollection::collection($this->submodule);
        }
      
        return $return;
    }
}


