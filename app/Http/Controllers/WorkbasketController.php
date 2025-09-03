<?php

namespace App\Http\Controllers;



use App\Models\Role;
use App\Models\User;
use App\Models\UserRole;
use App\Models\Workbasket;
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

        $frontliner = Auth::user()->roles->contains('id', Role::FRONTLINER);

        $data = Workbasket::when($frontliner, function ($query)  {
                                return $query->whereIn('status',[Workbasket::NEW,Workbasket::IN_PROGRESS]);
                            })
                            ->when(!$frontliner, function ($query)  {
                                return $query->where('handle_by', $user->id);
                            })
                            ->orderBy('updated_at','desc')
                            ->paginate($limit);

        return new WorkbasketCollection($data);
    }
}
