<?php

namespace App\Http\Services;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Http\Traits\ResponseTrait;
use App\Http\Resources\IncidentResources;
use App\Models\Incident;
use App\Models\Complaint;
use App\Models\IncidentResolution;
use App\Models\Sla;
use App\Models\SlaVersion;
use App\Models\Category;
use App\Models\OperatingTime;
use App\Models\SlaTemplate;
use App\Models\Workbasket;
use App\Models\Role;
use App\Models\User;
use Carbon\Carbon;

class IncidentServices
{
    public static function index($request){
        
        $limit = $request->limit ? $request->limit : 15;
        
        $data =  Incident::paginate($limit);

        return $data;
    }
    
    public static function create($data,$request){
        $category_code = isset($data['category']) ? $data['category'] : null;
        $received_via = null;

        DB::beginTransaction();

        if(!isset($data['complaint_id'])){

            $complaint = Complaint::create($data);

            $data['complaint_id'] =  $complaint->id;
        }
        else{
            $complaint = Complaint::where('id',$data['complaint_id'])->first();

            $user_details = User::where('id',$data['complaint_id'])->first();

            if(!$complaint && $user_details){

                $data_complaint['name'] = $user_details->name;
                $data_complaint['email'] = $user_details->email;
                $data_complaint['phone_no'] = $user_details->phone_no;
                $data_complaint['address'] = $user_details->address;
                $data_complaint['state_id'] = $user_details->state_id;
                $data_complaint['postcode'] = $user_details->postcode;

                $complaint = Complaint::create($data_complaint);

                $data['complaint_id'] =  $complaint->id;

                $data['complaint_user_id'] = $user_details->id;
            }
        }
        if($category_code){
            $category = Category::whereRaw('LOWER(name) = ?', [strtolower($category_code)])->first();


            $data['category_id'] = $category?->id;

            if($category_code == Category::MOBILE) {
                $received_via = Incident::RECIEVED_PHONE;
            }
            elseif($category_code == Category::SISTEM) {
                $received_via = Incident::RECIEVED_SYSTEM;
            } 
        }

        $data['sla_version_id'] = self::getSlaVersion($data);
        $data['expected_end_date'] = self::calculateDueDateIncident($data);
        
        // $data['service_recipient_id'] = $data['service_recipient_id'] ?? $data['operation_user_id'] ?? null;
        $data['received_via'] = $data['received_via'] ?? $received_via ?? null;

        $data['incident_date'] = date('Y-m-d H:i:s');
       
        $data = self::uploadDoc($data,$request);

        $create = Incident::create($data);

        $create_resolution = self::createResolution($create->id);

        $create_workbasket = self::createWorkbasket($create->id);

        $create->refresh();

        $return = self::callAssetIncident($create);

        return $return;
    }

    public static function update(Incident $incident,$data,$request){
        DB::beginTransaction();

        $data['incident_no'] = $incident->incident_no;
        $data = self::uploadDoc($data,$request);

        if($incident->categoryDescription->name == 'MOBILE'){
            $data['sla_version_id'] = self::getSlaVersion($data);
            $data['expected_end_date'] = self::calculateDueDateIncident($data);
        }

        $create = $incident->update($data);

        $return = self::callAssetIncident($incident);

        if($incident->status == Incident::RESOLVED || $incident->status == Incident::CLOSED){
            // self::calculatePenalty($incident);
        }

        return $return;
    }

    public static function view(Incident $incident){

        if (request()->source == 'workbasket') {

            $incident->workbasket()->update([
                'status' => Workbasket::OPENED,
            ]);
        }
        
        return  new IncidentResources($incident);
    }

    public static function delete($incident){

        if($incident->created_by != auth()->user()->id ){
            return false;
        }

        $incident->delete();

        return true;

    }

    public static function uploadDoc($data,$request){

        $destination = storage_path('app/private/incident'); 
        if (!file_exists($destination)) {
            mkdir($destination, 0777, true);
        }

        if ($request->hasFile('appendix_file') && $request->file('appendix_file')->isValid()){
            $file = $request->file('appendix_file');

            $mimeType = $request->file('appendix_file')->getClientOriginalExtension();
            $file_name = $data['incident_no'].'_appendix.'.$mimeType;

            // $path = $request->file('appendix_file')->storeAs('incident', $file_name, 'local');
            $file->move($destination, $file_name);
           
            $data['appendix_file'] = $file_name;
        }

        if ($request->hasFile('asset_file') && $request->file('asset_file')->isValid()){
            $file = $request->file('asset_file');

            $mimeType = $request->file('asset_file')->getClientOriginalExtension();
            $file_name = $data['incident_no'].'_asset_file.'.$mimeType;

            // $path = $request->file('asset_file')->storeAs('private/incident_asset', $file_name);
            $file->move($destination, $file_name);

            $data['asset_file'] = $file_name;
        }

        return $data;
    }

    public static function createResolution($id){
        
        $data['incident_id'] = $id;
        $data['operation_user_id'] = 1;
        $data['action_codes'] = 'INIT';
    
        IncidentResolution::create($data);

        return true;
    }

    public static function createWorkbasket($id){
        $data['date'] = date('Y-m-d H:i:s');
        $data['incident_id'] = $id;

        Workbasket::create($data);

        return true;
    }

    public static function callAssetIncident($data){
        $data_return = new IncidentResources($data);

        $return['data'] = $data_return;

        if(isset($data->asset_parent_id) || isset($data->asset_component_id) ){
            
            $asset_service = new AssetServices();

            $call_api_asset = $asset_service->createIncident($data);

            if($call_api_asset['data'] == null){
                DB::rollBack();

                $return['data'] = null;
                $return['message'] = $call_api_asset['message'];
                $return['status_code'] = 500;
            }
            else{
                DB::commit();
            }
        }
        else{
            DB::commit();
        }
        return $return;
    }
    
    public static function getSlaVersion($data){

        $get_sla = Sla::where('code',$data['code_sla'])->first();

        $get_sla_details = SlaVersion::where('sla_template_id',$get_sla?->sla_template_id)->orderBy('version','desc')->first();

        return $get_sla_details?->id;

    }

    public static function calculateDueDateIncident($data){

        $get_sla_details = SlaVersion::where('id',$data['sla_version_id'])->first();

        if(!$get_sla_details) return null;
        
        $now = now();

        if($get_sla_details->resolution_time_type == SlaTemplate::SLA_TYPE_MINUTE){
            $unit = 'addMinutes';
        }
        elseif($get_sla_details->resolution_time_type == SlaTemplate::SLA_TYPE_HOUR){
            $unit = 'addHours';
        }
        else{
            $unit = 'addDay';
        }

        $due_date = $now->copy()->$unit((int) $get_sla_details->resolution_time);

        // $get_operating_time = OperatingTime::whereRaw("JSON_EXISTS(branch_id, '\$[*] ? (@ == $data[branch_id])')")->where('duration',OperatingTime::NORMAL_DAY)->first();
        
        // if($get_operating_time){
        //     $start = Carbon::createFromFormat('H:i', $get_operating_time->operation_start);
        //     $end   = Carbon::createFromFormat('H:i', $get_operating_time->operation_end);

        //     $total_office_hours = $start->diffInHours($end);

        //     // dd($get_operating_time);
        // }


        while ($due_date->isWeekend()) {
            $due_date->addDay();
        }
            
        return $due_date->format('Y-m-d H:i:s');
    }

    public static function calculatePenalty(){

    }
} 