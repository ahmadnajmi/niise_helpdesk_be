<?php

namespace App\Http\Collection;

use Illuminate\Http\Request;
use App\Models\UserRole;
use App\Models\Module;
use App\Models\User;
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
                'name' => $query->translated_name,
                'description' => $query->description,
                'is_active' => $query->is_active,
                'modules' => $query->modules->pluck('module')->unique()->map(fn($module) => $module->translated_name)->values(),
                'total_permission' => $query->permissions->count(),
                'total_user' => $query->users->count(),
                'created_by' => $query->createdBy?->name .' - '. $query->createdBy?->email ,
                'updated_by' => $query->updatedBy?->name .' - '. $query->updatedBy?->email ,
                'created_at' => $query->created_at->format('d-m-Y'),
                'updated_at' => $query->updated_at->format('d-m-Y'),
            ];
            return $return;
        });
    }
}
