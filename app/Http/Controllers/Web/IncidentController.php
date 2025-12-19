<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Incident;
use App\Models\OperatingTime;
use App\Models\Calendar;
use App\Models\SlaVersion;
use App\Models\Branch;
use App\Models\SlaTemplate;
use App\Http\Services\IncidentServices;
use Carbon\Carbon;

class IncidentController extends Controller
{
    
    public function index(Request $request)
    {
        $public_holiday = [];
        $generate_due_date = null;
        $get_incident = Incident::where('incident_no',$request->incident_no)->first();

        $operating_time = OperatingTime::where('branch_id',$get_incident?->branch_id)->get();

        $state_id = $get_incident?->branch?->state_id;

        if($state_id){
            $public_holiday = Calendar::where(function($query) use ($state_id) {
                                        $query->whereRaw("JSON_EXISTS(state_id, '$?(@ == 0)')") 
                                        ->orWhereRaw("JSON_EXISTS(state_id, '$?(@ == $state_id)')"); 
                                    })
                                    ->get();
        }

        if($get_incident){
            $generate_due_date = IncidentServices::calculateDueDateIncident($get_incident);
        }

        return view('incident.index',compact('get_incident','operating_time','public_holiday','generate_due_date'));
    }

    public function generateDueDateIncident(Request $request){

        $generate_due_date = null;
        $sla_version = null;
        $public_holiday = [];
        $incident_date = $request->incident_date ? Carbon::parse($request->incident_date) : Carbon::now();

        $data['sla_template_id'] = $request->sla_template_id;
        $data['incident_date'] = $incident_date;
        $data['branch_id'] = $request->branch_id;

        if($data['sla_template_id'] &&  $data['branch_id']){
            $sla_version = SlaVersion::where('sla_template_id',$data['sla_template_id'])->orderBy('version','desc')->first();
            $data['sla_version_id'] = $sla_version?->id;
            $generate_due_date = IncidentServices::calculateDueDateIncident($data);
        }

        $list_sla_template = SlaTemplate::get();
        $list_branch = Branch::whereHas('operatingTime')->get();

        $operating_time = OperatingTime::where('branch_id',$request->branch_id)->get();

        $branch_details = Branch::where('id',$request->branch_id)->first();
        
        if($branch_details){
            $state_id = $branch_details->state_id;
            $public_holiday = Calendar::where('start_date','>=',$incident_date)
                                    ->when($generate_due_date, function ($query) use ($generate_due_date) {
                                        return $query->where('end_date','<=',$generate_due_date); 
                                    })
                                    ->where(function($query) use ($state_id) {
                                        $query->whereRaw("JSON_EXISTS(state_id, '$?(@ == 0)')")
                                        ->orWhereRaw("JSON_EXISTS(state_id, '$?(@ == $state_id)')");
                                    })
                                    ->get();
        }

        return view('incident.generate_duedate',compact('generate_due_date','list_branch','list_sla_template','operating_time','public_holiday','incident_date','sla_version'));
    }
}
