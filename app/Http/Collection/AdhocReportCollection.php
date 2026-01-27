<?php

namespace App\Http\Collection;

use Illuminate\Http\Request;

class AdhocReportCollection extends BaseResource
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
                'name_en' => $query->name_en,
                'code' => $query->code,
                'output_name' => $query->output_name,
                'file_name' => $query->file_name,
                'path' => $query->path,
                'is_default' => $query->is_default,
                'is_active' => $query->is_active == 1 ? true : false,
                'created_by' => $query->createdBy?->name .' - '. $query->createdBy?->email ,
                'updated_by' => $query->updatedBy?->name .' - '. $query->updatedBy?->email ,
            ];
            return $return;
        });
    }
}
