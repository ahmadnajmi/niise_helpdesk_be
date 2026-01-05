<?php

namespace App\Http\Collection;

use Illuminate\Http\Request;
use App\Http\Resources\ActionCodeResources;
use Illuminate\Support\Facades\Auth;
use App\Models\Role;
use App\Models\User;
use App\Models\UserGroup;
use App\Models\ActionCode;

class IncidentResolutionCollection extends BaseResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request)
    {
        $role = User::getUserRole(Auth::user()->id);

        return $this->collection->transform(function ($query) use($request,$role){
            $permission_edit = false;

            if($role?->role == Role::CONTRACTOR){
                if ($query->incident->incidentResolution->contains('action_codes', ActionCode::ACTR)) {
                    $permission_edit = false;
                } 
                else{
                    $group_id = UserGroup::where('user_id',$query->created_by)->where('groups_id',$query->group_id)->exists();

                    $permission_edit = $group_id ? true : false;
                }
            }
            elseif($role?->role == Role::BTMR && $role?->role == Role::FRONTLINER){
                $permission_edit = true;
            }

            $return =  [
                'id' => $query->id,
                'action_codes'=> $query->action_codes,
                'action_codes_details' => new ActionCodeResources($query->actionCodes),
                'solution_notes'=> $query->solution_notes,
                'permission_edit' => $permission_edit,
                'created_at' => $query->created_at->format('d-m-Y H:i:s'),
                'updated_at' => $query->updated_at->format('d-m-Y H:i:s'),
                'created_by' => $query->createdBy?->name .' - '. $query->createdBy?->email ,
                'updated_by' => $query->updatedBy?->name .' - '. $query->updatedBy?->email ,
            ];
            return $return;
        });
    }
}
