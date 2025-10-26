<?php

namespace App\Http\Controllers;

use App\Http\Traits\ResponseTrait;
use Illuminate\Http\Request;
use OwenIt\Auditing\Models\Audit;
use App\Http\Collection\AuditTrailCollection;
use App\Http\Resources\AuditTrailResources;

use Illuminate\Support\Facades\Auth;

class AuditController extends Controller
{
    use ResponseTrait;

    public function index(Request $request)
    {
        $limit = $request->limit ? $request->limit : 15;

        $data =  Audit::where('user_id',Auth::user()->id)->latest()->paginate($limit);

        return new AuditTrailCollection($data);

    }

    public function show($id)
    {
        $data = Audit::find($id);

        $data = new AuditTrailResources($data);

        return $this->success('Success', $data);
    }

}
