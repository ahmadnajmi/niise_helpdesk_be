<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ModuleResources extends JsonResource
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
            'svg_path' => $this->svg_path,
            'is_active' => $this->is_active,
            'route_name' =>  $this->route?->name,
            'created_by' => $this->createdBy->name .' - '. $this->createdBy->email ,
            'updated_by' => $this->updatedBy->name .' - '. $this->updatedBy->email ,
        ];

        if($request->route()->getName() == 'module.index'){
            $return['total_permissions'] = $this->permissions->count()  + $this->getTotalSubModuleCountAttribute();
            $return['total_roles_can_access'] = $this->roles->count();
            $return['total_users_can_access'] = rand(5,10);
            $return['sub_modules'] = $this->submodule->pluck('name');
            // $return['permissions'] = PermissionResources::collection($this->permissions);

        }
        elseif($request->route()->getName() == 'role.show' || $request->route()->getName() == 'module.show'){
            $return['sub_modules'] = ModuleResources::collection($this->submodule);
            $return['permissions'] = PermissionResources::collection($this->permissions);

        }
        if($request->route()->getName() == 'navigation.index'){
            $return['permissions'] = new PermissionResources($this->route);
            $return['sub_modules'] = ModuleResources::collection($this->submodule);
        }
        if($request->route()->getName() == 'module.show'){
            $return['permissions'] = PermissionResources::collection($this->permissions);
        }
      
        return $return;
    }
}


