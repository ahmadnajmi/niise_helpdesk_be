<?php

namespace App\Http\Services;
use App\Http\Collection\WorkbasketCollection;
use App\Http\Traits\ResponseTrait;
use App\Models\Workbasket;
use App\Models\User;
use App\Models\UserGroup;
use App\Models\Role;
use Illuminate\Support\Facades\Auth;

class WorkbasketServices
{
    use ResponseTrait;
    
    public static function index($request){

        $limit = $request->limit ? $request->limit : 15;
        
        $role = User::getUserRole(Auth::user()->id);
        
        $data = Workbasket::when($role?->role == Role::CONTRACTOR, function ($query) {
                                return $query->whereHas('incident', function ($query) {
                                    $group_id = UserGroup::where('user_id',Auth::user()->id)->pluck('groups_id');

                                    $query->whereIn('assign_group_id',$group_id);
                                })->where('status',Workbasket::NEW)->where('escalate_frontliner',false);
                            })
                            ->when($role?->role == Role::JIM, function ($query) {
                                return $query->whereHas('incident', function ($query) {
                                    $query->where('complaint_user_id',Auth::user()->id);
                                });
                            })
                            ->when($role?->role == Role::BTMR, function ($query) {
                                return $query->whereHas('incident', function ($query) {
                                    $query->where('created_by',Auth::user()->id);
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