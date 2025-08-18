<?php

namespace App\Http\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Http\Traits\ResponseTrait;
use App\Http\Resources\IncidentResources;
use App\Models\Incident;
use App\Models\Complaint;
use App\Models\IncidentSolution;
use App\Models\Sla;
use App\Models\Category;

class IncidentServices
{
    
    public static function create($data,$request){

        DB::beginTransaction();

        if(!isset($data['complaint_id'])){

            $complaint = Complaint::create($data);

            $data['complaint_id'] =  $complaint->id;
        }

        if(!isset($data['category_id'])){

            $category = Category::where('name','MOBILE')->first();

            $data['category_id'] = $category?->id;
        }

        // $get_sla = Sla::where('category_id',$data['category_id'])->where('branch_id',$data['branch_id'])->first();
        
        $data['start_date'] = date('Y-m-d H:i:s');
        $data['incident_no'] = self::generateCode();
        // $data['code_sla'] = $get_sla?->code;

        $data = self::uploadDoc($data,$request);

        $create = Incident::create($data);

        $create_resolution = self::createResolution($create->id);

        $create->refresh();

        $return = self::callAssetIncident($create);

        return $return;
    }

    public static function update(Incident $incident,$data){
        DB::beginTransaction();

        $create = $incident->update($data);

        $return = self::callAssetIncident($incident);

        return $return;
    }

    public static function delete($incident){

        if($incident->created_by != auth()->user()->id ){
            return false;
        }

        $incident->delete();

        return true;

    }

    public static function uploadDoc($data,$request){
        $destination = storage_path('app/private/incident'); // full path
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

    public static function generateCode(){

        $get_incident = Incident::orderBy('incident_no','desc')->first();

        if($get_incident){
            $code = $get_incident->incident_no;

            $old_code = substr($code, -5);

            $incremented = (int)$old_code + 1;

            $next_number = str_pad($incremented, 5, '0', STR_PAD_LEFT);
        }
        else{
            $next_number = '00001';
        }

        $new_code = 'TN'.date('Ymd').$next_number;
        
        return $new_code;
    }
    
    public static function createResolution($id){
        
        $data['incident_id'] = $id;
        $data['operation_user_id'] = 1;
        $data['action_codes'] = 'INIT';
    
        IncidentSolution::create($data);

        return true;
    }

    public static function callAssetIncident($data){
        $data_return = new IncidentResources($data);

        $return['data'] = $data_return;

        if(isset($data->asset_parent_id) || isset($data->asset_component_id)){
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
} 