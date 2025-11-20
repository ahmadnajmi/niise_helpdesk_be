<?php

namespace App\Http\Services;

use Illuminate\Support\Facades\Mail;
use App\Http\Traits\ResponseTrait;
use App\Models\Incident;
use App\Models\IncidentResolution;
use App\Models\ActionCode;
use App\Models\IncidentPenalty;
use App\Models\SlaVersion;
use App\Models\SlaTemplate;
use App\Models\User;
use App\Models\Workbasket;
use App\Models\EmailTemplate;
use App\Http\Resources\IncidentResolutionResources;
use App\Mail\ActionCodeEmail;

class IncidentResolutionServices
{
    use ResponseTrait;

    public static function create($data){

        try{
            $create = IncidentResolution::create($data);

            self::actionCode($create);

            // self::checkPenalty($create);

            $return = new IncidentResolutionResources($create);

            return self::success('Success', $return);
        }
        catch (\Throwable $th) {
            return self::error($th->getMessage());
        }
    }

    public static function update(IncidentResolution $incident_solution,$data){

        try{
            $create = $incident_solution->update($data);

            self::actionCode($incident_solution);

            // self::checkPenalty($incident_solution);

            $return = new IncidentResolutionResources($incident_solution);

            return self::success('Success', $return);
        }
        catch (\Throwable $th) {
            return self::error($th->getMessage());
        }
        
    }


    public static function actionCode($data){
        $incident = $data->incident;

        if($data->action_codes == ActionCode::ACTR || $data->action_codes == ActionCode::CLOSED){

            $data_incident['status']  = $data->action_codes == ActionCode::ACTR ? Incident::RESOLVED : Incident::CLOSED; 

            if($data->action_codes == ActionCode::CLOSED){
                $data_incident['resolved_user_id'] = auth()->user()->id;
            }
            $incident->update($data_incident);
        }

        if($incident->status == Incident::CLOSED){
            $incident->workbasket?->delete();

            $data_incident['actual_end_date']  = date('Y-m-d H:i:s');

            $incident->update($data_incident);
        }
        elseif($data->action_codes == ActionCode::RESOLVED || $data->action_codes == ActionCode::ACTR || $data->action_codes == ActionCode::ESCALATE){
            $incident->workbasket()->update([
                'status' => Workbasket::NEW,
                'status_complaint' => Workbasket::IN_PROGRESS
            ]);
        }
        else{
            $incident->workbasket()->update([
                'status' => Workbasket::IN_PROGRESS,
                'status_complaint' => Workbasket::IN_PROGRESS
            ]);
        }

        if($data->actionCodes->send_email){
            self::sendEmail($data);
        }

        return true;
    }

   
    public static function sendEmail($data){

        $group_member =  User::whereHas('group', function ($query)use($data) {
                                        $query->where('groups_id',$data->group_id);
                                })
                                ->pluck('email');

        $user_operation = User::where('id',$data->operation_user_id)->first();

        $email_complaint = [$data->incident->complaintUser?->email];
        
        if($data->actionCodes->email_recipient_id == ActionCode::SEND_TO_COMPLAINT){
            $send_to = $email_complaint;
            $cc_to = $group_member;
            $bc_to   = [];
        }
        elseif($data->actionCodes->email_recipient_id == ActionCode::SEND_TO_GROUP){
            $send_to = [$user_operation?->email];
            $cc_to = $group_member;
            $bc_to = $email_complaint;
        }
        else{
            $send_to = [$user_operation?->email];
            $cc_to = $group_member;
        }

        $email_template = EmailTemplate::select('id','sender_name','sender_email','notes')->where('is_active',true)->first();

        if (count(array_filter($send_to)) > 0) {
            Mail::to($send_to)
                ->cc($cc_to ?? [])
                ->bcc($bc_to ?? [])
                ->queue(new ActionCodeEmail($data->incident,$email_template));
        }
        

        return true;
    }
} 