<?php

namespace App\Http\Controllers;

use App\Http\Traits\ResponseTrait;
use Illuminate\Http\Request;
use App\Http\Collection\BranchCollection;
use App\Http\Resources\BranchResources;
use OwenIt\Auditing\Models\Audit;

class AuditController extends Controller
{
    use ResponseTrait;

    public function index(Request $request)
    {
        $limit = $request->limit ? $request->limit : 15;

        $data =  Audit::with('user')->latest()->paginate($limit);

        // return new UserCollection($data);

        return $this->success('Success', $data);
    }

    public function show($id)
    {
        $data = Audit::with('user')->find($id);

        // $data = new BranchResources($Branch);

        return $this->success('Success', $data);
    }

}
