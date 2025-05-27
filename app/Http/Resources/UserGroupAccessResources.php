<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserGroupAccessResources extends JsonResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        $return  = [
            'groups_id' => $this->groups_id,
            'user_id' => $this->id,
        ];

        if($request->route()->getName() == 'group.show'){
            $return['user_details'] = $this->userDetails ? new UserResources($this->userDetails) : null ;
        }

        if($request->route()->getName() == 'user.show'){
            $return['group_details'] = new GroupResources($this->groupDetails);
        }
        return $return;
    }
}
