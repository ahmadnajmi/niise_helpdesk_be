<?php

namespace App\Http\Controllers;



use App\Models\Role;
use App\Models\User;
use App\Models\UserRole;
use App\Models\Workbasket;
use  App\Models\UserGroup;
use Illuminate\Http\Request;
use App\Http\Traits\ResponseTrait;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\WorkbasketRequest;
use App\Http\Resources\WorkbasketResources;
use App\Http\Collection\WorkbasketCollection;
use App\Http\Services\JasperServices;

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

        $data = Workbasket::where(function ($query) use ($role,$group_id) {
                                $query->when($role?->role == Role::FRONTLINER, function ($q) {
                                    return $q->whereIn('status', [Workbasket::NEW, Workbasket::IN_PROGRESS]);
                                })
                                ->when($role?->role != Role::FRONTLINER, function ($q) {
                                    return $q->where('handle_by', Auth::id());
                                })
                                ->when($role?->role == Role::CONTRACTOR, function ($query)use($group_id) {
                                    return $query->whereHas('incident', function ($query)use($group_id) {
                                            $query->whereHas('incidentResolution', function ($query) use($group_id){
                                                $query->whereIn('group_id',$group_id); 
                                        }); 
                                    });
                                });
                            })
                            ->orWhere(function ($query) {
                                $query->whereHas('incident', function ($query) {
                                    $query->where('complaint_user_id',Auth::user()->id);
                                });
                            })
                           
                            ->orderBy('updated_at','desc')
                            ->paginate($limit);

        return new WorkbasketCollection($data);
    }
}
