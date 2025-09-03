<?php

namespace App\Http\Collection;

use Illuminate\Http\Request;

class WorkbasketCollection extends BaseResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request)
    {
        return $this->collection->transform(function ($query) use($request){
            $return =  [
                'id' => $query->id,
                'date' => $query->date,
                'incident_id' => $query->incident_id,
                'handle_by' => $query->handle_by,
                'status' => $query->status,
                'created_at' => $query->created_at->format('d-m-Y'),
                'updated_at' => $query->updated_at->format('d-m-Y'),
            ];
            return $return;
        });
    }
}
