<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\UserRole;
use  App\Models\Module;
use App\Models\User;

class RoleResources extends JsonResource
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
            'name' => $this->name,
            'name_en' => $this->name_en,
            'description' => $this->description,
            'is_active' => $this->is_active,
            'created_by' => $this->createdBy->name .' - '. $this->createdBy->email ,
            'updated_by' => $this->updatedBy->name .' - '. $this->updatedBy->email ,
            'created_at' => $this->created_at->format('d-m-Y'),
            'updated_at' => $this->updated_at->format('d-m-Y'),
        ];

        if($request->route()->getName() == 'role.index'){
            $return['modules'] =  $this->modules->pluck('module')->unique()->map(fn($module) => $module->translated_name)->values();
            $return['total_permission'] = $this->permissions->count();
            $return['total_user'] = $this->userRole->count();

        }
        elseif($request->route()->getName() == 'role.show'){
            $return['modules'] = ModuleResources::collection(Module::whereNull('module_id')->get());
            $return['permissions'] = $this->permissions;
            // $return['list_user'] = UserCollection::collection(User::whereIn('id',$user_role)->get());
        }

        

        return $return;
    }
}
