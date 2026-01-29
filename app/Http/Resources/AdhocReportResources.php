<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AdhocReportResources extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
         return [
            'id' => $this->id,
            'name' => $this->name,
            'name_en' => $this->name_en,
            'code' => $this->code,
            'output_name' => $this->output_name,
            'file_name' => $this->file_name,
            'path' => $this->path,
            'is_default' => $this->is_default,
            'is_active' => $this->is_active == 1,
            'created_at' => $this->created_at->format('d-m-Y'),
            'updated_at' => $this->updated_at->format('d-m-Y'),
        ];
    }
}
