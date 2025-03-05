<?php

namespace App\Http\Collection;

use Illuminate\Http\Request;

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
                'name' => $query->name,
                'position' => $query->position,
                'branch' => $query->branch,
                'email' => $query->email,
                'phone_no' => $query->phone_no,
                'created_at' => $query->created_at?->format('d-m-Y'),
                'updated_at' => $query->updated_at?->format('d-m-Y'),
            ];
            return $return;
        });
    }
}
