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
use App\Http\Services\WorkbasketServices;


class WorkbasketController extends Controller
{
    use ResponseTrait;

    public function index(Request $request)
    {
        $data = WorkbasketServices::index($request);

        return $data;

        $limit = $request->limit ?? 5;
       

    }
}
