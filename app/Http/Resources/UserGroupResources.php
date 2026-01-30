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
            'user_type' => $this->user_type,
            'ic_no' => $this->ic_no,
            'name' => $this->name,
            'email' => $this->email,
            'company_id' => $this->company_id,
        ];


        if($request->route()->getName() == 'user.show'){
            $return['group_details'] = new GroupResources($this->groupDetails);
        }
        return $return;
    }
}
