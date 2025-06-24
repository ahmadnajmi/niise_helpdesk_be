<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResources extends JsonResource
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
            'ic_no' => $this->ic_no,
            'name' => $this->name,
            'nickname'  =>$this->nickname,
            'position' => $this->position,
            'branch' => $this->branch,
            'email' => $this->email,
            'phone_no' => $this->phone_no,
            'address' => $this->address,
            'postcode' => $this->postcode,
            'city' => $this->city,
            'state_id' => $this->state_id,
            'state_desc' => $this->stateDescription?->name,
            'company_id' => $this->company_id,
            'company_name' => $this->company?->name,
            'fax_no' => $this->fax_no,
            'is_active' => $this->is_active,
            'role' => $this->id ? $this->getUserRole($this->id) : null,
            'created_at' => $this->created_at?->format('d-m-Y'),
            'updated_at' => $this->updated_at?->format('d-m-Y'),
        ];

        if($request->route()->getName() != 'group_management.show'){
            $return['group'] = UserGroupResources::collection($this->group) ;
            $return['group_access'] = UserGroupAccessResources::collection($this->groupAccess);
        }
        return $return;
    }
}
