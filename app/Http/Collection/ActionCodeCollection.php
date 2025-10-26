<?php

namespace App\Http\Collection;

use Illuminate\Http\Request;

class ActionCodeCollection extends BaseResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request)
    {
        return $this->collection->transform(function ($query) use($request){
            return [
                'id' => $query->id,
                'name' => $query->name,
                'nickname' => $query->nickname,
                'send_email' => $query->send_email,
                'email_recipient' => $query->email_recipient_id,
                'email_recipient_desc' => $query->emailRecipientDescription?->name,
                'skip_penalty' => $query->skip_penalty,
                'description' => $query->description,
                'is_active' => $query->is_active,
                'created_by' => $query->createdBy->name .' - '. $query->createdBy->email ,
                'updated_by' => $query->updatedBy->name .' - '. $query->updatedBy->email ,
                'created_at' => $query->created_at->format('d-m-Y H:i:s'),
                'updated_at' => $query->updated_at->format('d-m-Y H:i:s'),
            ];
        });
    }
}
