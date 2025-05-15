<?php

namespace App\Http\Collection;

use Illuminate\Http\Request;
use App\Http\Resources\ModuleResources;
use App\Http\Resources\PermissionResources;

class ModuleCollection extends BaseResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request)
    {
        return $this->collection->transform(function ($query) use($request){
            $return =  [
                'id' => $query->id,
                'name' => $query->translated_name,
                'description' => $query->description,
                'svg_path' => $query->svg_path,
                'is_active' => $query->is_active,
                'route_name' =>  $query->route?->name,
                'created_by' => $query->createdBy->name .' - '. $query->createdBy->email ,
                'updated_by' => $query->updatedBy->name .' - '. $query->updatedBy->email ,
                'created_at' => $query->created_at->format('d-m-Y'),
                'updated_at' => $query->updated_at->format('d-m-Y'),
            ];

            if($request->route()->getName() == 'module.index'){
                $return['total_permissions'] = $query->permissions->count()  + $query->getTotalSubModuleCountAttribute();
                $return['total_roles_can_access'] = $query->roles->count();
                $return['total_users_can_access'] = rand(5,10);
                $return['sub_modules'] = $query->submodule->map->translated_name;    
            }

            if($request->route()->getName() == 'navigation.index'){
                $return['permissions'] = new PermissionResources($query->route);
                $return['sub_modules'] = ModuleResources::collection($query->submodule);
            }
            return $return;
        });
    }
}


