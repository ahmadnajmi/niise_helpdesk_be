<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Collection\AuditTrailCollection;
use App\Http\Resources\AuditTrailResources;
use App\Http\Traits\ResponseTrait;
use App\Models\AuditTrail;

class AuditController extends Controller
{
    use ResponseTrait;

    public function index(Request $request)
    {
        $limit = $request->limit ? $request->limit : 15;

        $data =  AuditTrail::filter()->search($request->search)->sortByField($request)->paginate($limit);

        return new AuditTrailCollection($data);

    }

    public function show($id)
    {
        $data = Audit::find($id);

        $data = new AuditTrailResources($data);

        return $this->success('Success', $data);
    }

}
