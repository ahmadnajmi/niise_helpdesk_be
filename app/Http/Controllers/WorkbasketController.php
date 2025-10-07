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
use App\Http\Services\JasperServices;

class WorkbasketController extends Controller
{
    use ResponseTrait;

    public function index(Request $request)
    {
        $limit = $request->limit ?? 15;

        $frontliner = Auth::user()->roles->contains('role', Role::FRONTLINER);

        $data = Workbasket::where(function ($query) use ($frontliner) {
                                $query->when($frontliner, function ($q) {
                                    return $q->whereIn('status', [Workbasket::NEW, Workbasket::IN_PROGRESS]);
                                })
                                ->when(!$frontliner, function ($q) {
                                    return $q->where('handle_by', Auth::id());
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
