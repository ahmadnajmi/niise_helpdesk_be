<?php

namespace App\Http\Controllers;



use App\Models\Role;
use App\Models\User;
use App\Models\UserRole;
use App\Models\Workbasket;
use App\Models\UserGroup;
use App\Models\ActionCode;
use Illuminate\Http\Request;
use App\Http\Traits\ResponseTrait;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\WorkbasketRequest;
use App\Http\Resources\WorkbasketResources;
use App\Http\Collection\WorkbasketCollection;

class WorkbasketController extends Controller
{
    use ResponseTrait;

    public function index(Request $request)
    {
        $limit = $request->limit ?? 15;
        $group_id = [];

        $role = User::getUserRole(Auth::user()->id);

        if($role?->role == Role::CONTRACTOR){
            $group_id = UserGroup::where('user_id',Auth::user()->id)->pluck('groups_id');
        }
        
        $data = Workbasket::when($role?->role == Role::CONTRACTOR, function ($query)use($group_id) {
                                $group_id = UserGroup::where('user_id',Auth::user()->id)->pluck('groups_id');
                                ///kalau contractor esclate or actr ke frontliner kat wb still muncul
                                //tapi kena ingat yg contractor boleh wat verify...kalau wat verify boleh hilang wb kalau tukar ke function incidentResolutionlatest
                                return $query->where('status',Workbasket::NEW)
                                            ->whereHas('incident', function ($query)use($group_id) {
                                            $query->whereHas('incidentResolutionEscalateLatest', function ($query) use($group_id){
                                                $query->whereIn('group_id',$group_id);
                                        }); 
                                });
                            })
                            ->when($role?->role == Role::JIM || $role?->role == Role::BTMR  , function ($query) {
                                return $query->whereHas('incident', function ($query) {
                                    $query->where('complaint_user_id',Auth::user()->id);
                                });
                            })
                            ->when($role?->role == Role::FRONTLINER, function ($query) {
                                return $query->where('escalate_frontliner',true)->where('status',Workbasket::NEW);
                            })
                            ->orderBy('updated_at','desc')
                            ->paginate($limit);

        return new WorkbasketCollection($data);
    }
}
