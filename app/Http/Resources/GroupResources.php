<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GroupResources extends JsonResource
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
            'created_by' => $this->createdBy->name .' - '. $this->createdBy->email ,
            'updated_by' => $this->updatedBy->name .' - '. $this->updatedBy->email ,
            'created_at' => $this->created_at->format('d-m-Y'),
            'updated_at' => $this->updated_at->format('d-m-Y'),
        ];

        if($request->route()->getName() != 'user.show'){
            $return['user_group'] = UserGroupResources::collection($this->UserGroup()->whereHas('userDetails')->get());
            $return['user_group_access'] = UserGroupAccessResources::collection($this->userGroupAccess()->whereHas('userDetails')->get());
        }

        return $return;
    }
}
