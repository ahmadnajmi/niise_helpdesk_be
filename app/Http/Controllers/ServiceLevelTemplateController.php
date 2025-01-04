<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use App\Models\ServiceLevelTemplate;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use App\Http\Traits\ResponseTrait;

class ServiceLevelTemplateController extends Controller
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
        $slatemplateList = (new ServiceLevelTemplate())->getSlaTemplateIndex()->paginate($perPage);
       
        log::info($slatemplateList);
        foreach($slatemplateList as $sla){
            if ($sla->st_due_date_timeframe != 0) {
                $duedatetimeframe = (new ServiceLevelTemplate())->getTimeType($sla->st_due_date_timeframe);
               
                $sla['duedatetimeframe'] = explode('^',$duedatetimeframe)[0];
                $sla['duedatetimeframeType'] = explode('^',$duedatetimeframe)[1];
            } else {
                $sla['duedatetimeframe'] = '0';
                $sla['duedatetimeframeType'] = 's';
            }
            if ($sla->st_escalation_time != 0) {
                $escalationtime = (new ServiceLevelTemplate())->getTimeType($sla->st_escalation_time);
               
                $sla['escalationtime'] = explode('^',$escalationtime)[0];
                $sla['escalationtimeType'] = explode('^',$escalationtime)[1];
            }
        }


        return $this->success('Success', $slatemplateList);
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
