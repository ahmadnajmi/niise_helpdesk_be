<?php

namespace App\Http\Collection;

use Illuminate\Http\Request;
use App\Http\Resources\UserResources;

class AuditTrailCollection extends BaseResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request)
    {
        return $this->collection->transform(function ($query) use($request){
            //testing
            return [
                'id' => $query->id,
                'user_type' => $query->user_type,
                'event' => $query->event,
                'auditable_type' => $query->auditable_type,
                'old_values' => $query->old_values,
                'new_values' => $query->new_values,
                'user_details' => $query->user ? new UserResources($query->user) : null,
                'created_at' => $query->created_at->format('d-m-Y H:i:s'),
                'updated_at' => $query->updated_at->format('d-m-Y H:i:s'),
            ];
        });
    }
}
