<?php

namespace App\Http\Collection;

use Illuminate\Http\Request;
use App\Http\Resources\ModuleResources;
use App\Http\Resources\PermissionResources;
use App\Http\Resources\OperatingTimeResources;

class BranchCollection extends BaseResource
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
                'name' => $query->name,
                'category' => $query->category,
                'state' => $query->state,
                'location' => $query->location,
            ];

            if($request->route()->getName() == 'operating_time.show' || $request->route()->getName() == 'operating_time.index'){
                $return['operating_times'] = count($query->operatingTime) > 0 ? new OperatingTimeCollection($query->operatingTime) : [] ;

            }

            return $return;
        });
    }                

}

