<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use App\Models\KnownError;
use App\Models\ServiceCategory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use App\Http\Traits\ResponseTrait;

class KnownErrorController extends Controller
{
    use ResponseTrait;
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if(isset($request->perPage)) {
            $perPage = $request->perPage;
        } else {
            $perPage = 2;
        }
            $knownError = (new KnownError())->getAllKnownError()->paginate($perPage);


        return $this->success('Success', $knownError);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id, Request $request)
    {
        if(isset($request->perPage)) {
            $perPage = $request->perPage;
        } else {
            $perPage = 2;
        }
            $data = (new KnownError())->getKnownError($id);


        return $this->success('Success', $data);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit()
    {
        $Category = ServiceCategory::select("Ct_Code", "Ct_Description", "Ct_Abbreviation")->orderByRaw("CAST(SUBSTRING(Ct_Code, 1, CHARINDEX('-', Ct_Code + '-') - 1) AS INT),  Ct_Code")->get();
        log::info($Category);
        return $this->success('Success', $Category);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        log::info($id);
        $data = KnownError::destroy($id);
        return redirect()->back();
    }
}
