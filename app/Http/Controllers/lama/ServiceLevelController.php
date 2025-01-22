<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use App\Models\ServiceLevel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use App\Http\Traits\ResponseTrait;

class ServiceLevelController extends Controller
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
            $SLA = (new ServiceLevel())->getSlaIndex()->paginate($perPage);
        
        log::info($SLA);

        return $this->success('Success', $SLA);
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
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
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
        //
    }
}
