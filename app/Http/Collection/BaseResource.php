<?php

namespace App\Http\Collection;

use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class BaseResource extends ResourceCollection
{
    public function __construct($resource,$status = 200,$message = 'Success',$custom_data = null)
    {
        parent::__construct($resource);
        $this->message = $message; 
        $this->status = $status; 
        $this->custom_data = $custom_data; 

    }

    public function toResponse($request)
    {
        $data = $this->custom_data ? $data = $this->resource->getCollection() : $this->toArray($request);

        return response()->json([
            'status' => true,
            'status_code' => $this->status,
            'message' => $this->message ,
            'data' => $data,
            'meta' => [
                'current_page' => $this->resource->currentPage(),
                'last_page' => $this->resource->lastPage(),
                'per_page' => $this->resource->perPage(),
                'total' => $this->resource->total(),
                'from' => $this->resource->firstItem(),
                'to' => $this->resource->lastItem(),
            ],
            'links' => [
                'first' => $this->resource->url(1),
                'last' => $this->resource->url($this->resource->lastPage()),
                'prev' => $this->resource->previousPageUrl(),
                'next' => $this->resource->nextPageUrl(),
            ]
        ]);
    }
}
