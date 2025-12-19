<?php

namespace App\Http\Collection;

use Illuminate\Http\Request;

class PermissionCollection extends BaseResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request)
    {
        return $this->collection->transform(function ($query) use($request){
            return [
                'id' => $query->id,
                'name' => $query->name,
                'description' => $query->description
            ];
        });
    }
}
