<?php

namespace App\Http\Services;

use Illuminate\Support\Facades\Mail;
use App\Models\Incident;
use App\Models\IncidentSolution;
use App\Http\Resources\IncidentSolutionResources;
use App\Mail\ActionCodeEmail;

class IncidentSolutionServices
{
    
    public static function create($data){

        $create = IncidentSolution::create($data);

        self::actionCode($create);

        $return = new IncidentSolutionResources($create);

        return $return;
    }

    public static function update(IncidentSolution $incident_solution,$data){

        $create = $incident_solution->update($data);

        self::actionCode($incident_solution);

        $return = new IncidentSolutionResources($incident_solution);

        return $return;
    }


    public static function actionCode($data){

        if($data->action_codes == 'ACTR' || $data->action_codes == 'CLSD'){
            $incident = $data->incident;

            $data_incident['status']  = $data->action_codes == 'ACTR' ? Incident::RESOLVED : Incident::CLOSED; 

            $incident->update($data_incident);
        }

        if($data->actionCodes->send_email){

            $send_email = Mail::to(auth()->user()->email)->send(new ActionCodeEmail());
        }

        return true;
    }
} 