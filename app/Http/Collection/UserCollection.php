<?php

namespace App\Http\Collection;

use Illuminate\Http\Request;
use App\Models\User;

class UserCollection extends BaseResource
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
                'ic_no' => $query->ic_no,
                'name' => $query->name,
                'nickname'  =>$query->nickname,
                'position' => $query->position,
                'branch' => $query->branch,
                'email' => $query->email,
                'phone_no' => $query->phone_no,
                'address' => $query->address,
                'postcode' => $query->postcode,
                'city' => $query->city,
                'state_id' => $query->state_id,
                'state_desc' => $query->stateDescription?->name,
                'fax_no' => $query->fax_no,
                'is_active' => $query->is_active,
                'role' => User::getUserRole($query->id),
                'created_at' => $query->created_at?->format('d-m-Y'),
                'updated_at' => $query->updated_at?->format('d-m-Y'),
            ];
            return $return;
        });
    }
}
