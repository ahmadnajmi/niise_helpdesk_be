<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Role;
use App\Models\UserGroup;
use App\Models\ActionCode;
class IncidentResolutionResources extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $role = User::getUserRole(Auth::user()->id);

        $permission_edit = false;

        if($role?->role == Role::CONTRACTOR){
            if ($this->incident->incidentResolution->contains('action_codes', ActionCode::ACTR)) {
                $permission_edit = false;
            } 
            else{
                $group_id = UserGroup::where('ic_no',$this->updatedBy?->ic_no)->where('groups_id',$this->group_id)->exists();

                $permission_edit = $group_id ? true : false;
            }
        }
        elseif($role?->role == Role::BTMR || $role?->role == Role::FRONTLINER){
            $permission_edit = true;
        }

        return [
            'id' => $this->id,
            'action_codes'=> $this->action_codes,
            'action_codes_details' => new ActionCodeResources($this->actionCodes),
            'group_id'=> $this->group_id,
            'group_details' => new GroupResources($this->group),
            'operation_user_id'=> $this->operation_user_id,
            'operation_user_details' => new UserResources($this->operationUser),
            'report_contractor_no'=> $this->report_contractor_no,
            'solution_notes'=> $this->solution_notes,
            'permission_edit' => $permission_edit,
            'notes'=> $this->notes,
            'created_by' => $this->createdBy?->name .' - '. $this->createdBy?->email ,
            'updated_by' => $this->updatedBy?->name .' - '. $this->updatedBy?->email ,
            'created_at' => $this->created_at->format('d-m-Y H:i:s'),
            'updated_at' => $this->updated_at->format('d-m-Y H:i:s'),
        ];
    }
}
