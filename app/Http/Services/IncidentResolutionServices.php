<?php

namespace App\Http\Services;

use Illuminate\Support\Facades\Mail;
use App\Models\Incident;
use App\Models\IncidentResolution;
use App\Models\ActionCode;
use App\Models\IncidentPenalty;
use App\Models\SlaVersion;
use App\Models\SlaTemplate;
use App\Http\Resources\IncidentResolutionResources;
use App\Mail\ActionCodeEmail;

class IncidentResolutionServices
{
    
    public static function create($data){

        $create = IncidentResolution::create($data);

        self::actionCode($create);

        // self::checkPenalty($create);

        $return = new IncidentResolutionResources($create);

        return $return;
    }

    public static function update(IncidentResolution $incident_solution,$data){

        $create = $incident_solution->update($data);

        self::actionCode($incident_solution);

        // self::checkPenalty($incident_solution);

        $return = new IncidentResolutionResources($incident_solution);

        return $return;
    }


    public static function actionCode($data){

        if($data->action_codes == 'ACTR' || $data->action_codes == 'CLSD'){
            $incident = $data->incident;

            $data_incident['status']  = $data->action_codes == 'ACTR' ? Incident::RESOLVED : Incident::CLOSED; 

            $incident->update($data_incident);
        }


        if($data->actionCodes->send_email){

            if($data->actionCodes->email_recipient_id == ActionCode::SEND_TO_COMPLAINT){
               $send_to = [$data->complaint->email];
            }
            elseif($data->actionCodes->email_recipient_id == ActionCode::SEND_TO_GROUP){

            }
            else{

            }

            $send_email = Mail::to(auth()->user()->email)->send(new ActionCodeEmail());
        }

        return true;
    }

    public static function checkPenalty($data){
        $get_sla_version = Incident::where('id',$data->incident_id)->first()->slaVersion;

        if($data->action_codes == ActionCode::INIT){

            if($get_sla_version->response_time_type == SlaTemplate::SLA_TYPE_MINUTE){
                $unit = 'addMinutes';
                $penalty_unit = 'diffInMinutes';
            }
            elseif($get_sla_version->response_time_type == SlaTemplate::SLA_TYPE_HOUR){
                $unit = 'addHours';
                $penalty_unit = 'diffInHours';
            }
            else{
                $unit = 'addDay';
                $penalty_unit = 'diffInDays';

            }
            $due_date = $data->created_at->copy()->$unit((int) $get_sla_version->response_time);

            if(now()->greaterThan($due_date)){
                $late_value    = $due_date->$penalty_unit(now()); 
                dd($late_value/$get_sla_version->response_time);
                $data_penalty['total_response_time_penalty_minute'] =  $dueDate->diffInMinutes(now());
                $data_penalty['total_response_time_penalty_price'] =  $late_value * (float) $get_sla_version->response_time_penalty;

                IncidentPenalty::create($data_penalty);
            }
        }

        dd('habis');

    }
} 