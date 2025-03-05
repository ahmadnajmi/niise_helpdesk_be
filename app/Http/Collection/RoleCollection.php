<?php

namespace App\Http\Collection;

use Illuminate\Http\Request;
use App\Models\UserRole;
use App\Models\Module;
use App\Models\IdentityManagement\User;
use App\Http\Resources\ModuleResources;

class RoleCollection extends BaseResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request)
    {
        return $this->collection->transform(function ($query) use($request){
            $return  = [
                'id' => $query->id,
                'name' => $query->name,
                'description' => $query->description,
                'is_active' => $query->is_active,
                'modules' => $query->modules->pluck('module')->unique()->pluck('name'),
                'total_permission' => $query->permissions->count(),
                'total_user' => $query->userRole->count(),
                'created_at' => $query->created_at->format('d-m-Y'),
                'updated_at' => $query->updated_at->format('d-m-Y'),
            ];
            return $return;
        });
    }
}
