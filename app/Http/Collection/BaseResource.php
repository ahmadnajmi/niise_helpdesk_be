<?php

namespace App\Http\Collection;

use Illuminate\Http\Resources\Json\ResourceCollection;

class BaseResource extends ResourceCollection
{
    public function __construct($resource,$status = 200,$message = 'Success')
    {
        parent::__construct($resource);
        $this->message = $message; 
        $this->status = $status; 
    }

    public function toResponse($request)
    {
        return response()->json([
            'status' => true,
            'status_code' => $this->status,
            'message' => $this->message ,
            'data' => $this->toArray($request),
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
