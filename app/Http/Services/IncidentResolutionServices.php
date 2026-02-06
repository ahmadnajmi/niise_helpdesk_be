<?php

namespace App\Http\Services;

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
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
use App\Models\Role;
use App\Models\UserGroup;
use App\Http\Resources\IncidentResolutionResources;
use App\Mail\ActionCodeEmail;
use App\Events\WorkbasketUpdated;
use App\Http\Services\IncidentServices;
use Carbon\Carbon;

class IncidentResolutionServices
{
    use ResponseTrait;

    public static function create($data){

        try{
            $create = IncidentResolution::create($data);

            self::actionCode($create);

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

            $return = new IncidentResolutionResources($incident_solution);

            return self::success('Success', $return);
        }
        catch (\Throwable $th) {
            return self::error($th->getMessage());
        }
        
    }

    public static function actionCode($data){
        $incident = $data->incident;
        $data_workbasket['status_complaint'] = Workbasket::IN_PROGRESS;

        $role = User::getUserRole(Auth::user()->id);

        $trigger_workbasket = [
            'frontliner' => false,
            'contractor' => false,
            'btmr' => false,
            'jim' => false
        ];

        
        if($role?->role == Role::CONTRACTOR){

            if($data->action_codes == ActionCode::ACTR){
                $data_incident['status']  =  Incident::RESOLVED; 
                $data_incident['resolved_user_id'] = auth()->user()->id;
                $data_incident['assign_company_id'] = auth()->user()->company_id;

                $data_workbasket['escalate_frontliner'] = true;
                $data_workbasket['status'] = Workbasket::NEW;

                $incident->update($data_incident);

                $trigger_workbasket['frontliner'] = true;
            }
            elseif($data->action_codes == ActionCode::RETURN) {
                $data_workbasket['escalate_frontliner'] = true;
                $data_workbasket['status'] = Workbasket::NEW;

                $trigger_workbasket['frontliner'] = true;
            }
            else{
                $data_workbasket['escalate_frontliner'] = false;
            }
        }
        elseif($data->action_codes == ActionCode::ACTR || $data->action_codes == ActionCode::ESCALATE){

            if($data->action_codes == ActionCode::ACTR){
                $data_incident['status']  =  Incident::RESOLVED; 
                $data_incident['resolved_user_id'] = auth()->user()->id;
                $data_incident['assign_company_id'] = auth()->user()->company_id;
            }
            else{
                $get_operation = UserGroup::where('id',$data->operation_user_id)->first();

                if(!$get_operation){
                    $get_operation = User::where('id',$data->operation_user_id)->first();
                }

                $data_incident['assign_group_id'] = $data->group_id;
                $data_incident['assign_company_id'] = $get_operation?->company_id;
                $data_incident['status'] = Incident::OPEN;

                $data_workbasket['status'] = Workbasket::NEW;

                $trigger_workbasket['contractor'] = true;
            }

            $data_workbasket['escalate_frontliner'] = false;
        }
        elseif($data->action_codes == ActionCode::CLOSED){
            $data_incident['status']  =  Incident::CLOSED; 
            $data_incident['actual_end_date'] = Carbon::now();

            $incident->workbasket?->delete();

            $trigger_workbasket['btmr'] = true;
            $trigger_workbasket['jim'] = true;

            IncidentServices::generatePenalty($incident);
        }
        elseif($data->action_codes == ActionCode::CNCLDUP){
            $data_incident['status']  =  Incident::CANCEL_DUPLICATE; 
            $incident->workbasket?->delete();
        }
        elseif($data->action_codes != ActionCode::DISC){
            $data_workbasket['status'] = Workbasket::IN_PROGRESS;
            $data_incident['status'] = Incident::OPEN;
        }



        if(isset($data_incident)){
            $incident->update($data_incident);
        }

        $incident->workbasket()->update($data_workbasket);

        if($data->actionCodes->send_email){
            self::sendEmail($data);
        }
        
        if (in_array(true, $trigger_workbasket, true)) {
            event(new WorkbasketUpdated($incident,$trigger_workbasket));
        }
        return true;
    }

    public static function sendEmail($data){

        $group_member =  UserGroup::where('groups_id',$data->group_id)
                                ->pluck('email');

        $user_operation = UserGroup::where('id',$data->operation_user_id)->first();

        if(!$user_operation){
            $user_operation = User::where('id',$user_operation->user_id)->first();
        }

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