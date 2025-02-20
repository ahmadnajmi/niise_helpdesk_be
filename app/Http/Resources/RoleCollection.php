<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\UserRole;
use App\Models\IdentityManagement\User;

class RoleCollection extends JsonResource
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
            'description' => $this->description,
            'is_active' => $this->is_active,
            'created_at' => $this->created_at->format('d-m-Y'),
            'updated_at' => $this->updated_at->format('d-m-Y'),
        ];

        if($request->route()->getName() == 'role.index'){
            $return['modules'] = $this->modules->pluck('module')->unique()->pluck('name');
            $return['total_permission'] = $this->permissions->count();
            $return['total_user'] = $this->userRole->count();

        }
        elseif($request->route()->getName() == 'role.show'){

            $user_role = UserRole::where('role_id',$this->id)->pluck('user_id');

            $return['modules'] = ModuleCollection::collection($this->modules->pluck('module')->unique());
            $return['permissions'] = $this->permissions;
            // $return['list_user'] = UserCollection::collection(User::whereIn('id',$user_role)->get());

        }

        

        return $return;
    }
}
