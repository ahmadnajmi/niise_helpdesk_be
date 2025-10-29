<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserGroupResources extends JsonResource
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
            'ic_no' => $this->ic_no,
        ];

        if($request->route()->getName() == 'group_management.show'){
            $return['user_details'] = $this->userDetails ? new UserResources($this->userDetails) : null ;
        }

        if($request->route()->getName() == 'user.show'){
            $return['group_details'] = new GroupResources($this->groupDetails);
        }
        return $return;
    }
}
