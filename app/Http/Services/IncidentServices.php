<?php

namespace App\Http\Services;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use App\Http\Traits\ResponseTrait;
use App\Http\Resources\IncidentResources;
use App\Models\Incident;
use App\Models\IncidentDocument;
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
use App\Models\Calendar;
use App\Models\Branch;
use App\Models\ActionCode;
use App\Models\IncidentPenalty;
use Carbon\Carbon;
use ZipArchive;

class IncidentServices
{
    use ResponseTrait;

    public static function index($request){
        
        $data =  Incident::filterIncident($request);

        return $data;
    }
    
    public static function create($data,$request){

        try{
            $category_code = isset($data['category']) ? $data['category'] : null;
            $received_via = null;

            DB::beginTransaction();

            if(!isset($data['complaint_user_id'])){

                $data['user_type'] = User::FROM_COMPLAINT;

                $complaint = User::create($data);

                $data['complaint_user_id'] =  $complaint->id;
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

            $data['incident_no'] = Incident::generateIncidentNo();
            $data['incident_date'] = Carbon::now();
            $data['sla_version_id'] = self::getSlaVersion($data);
            $data['expected_end_date'] = self::calculateDueDateIncident($data);

            // $data['service_recipient_id'] = $data['service_recipient_id'] ?? $data['operation_user_id'] ?? null;
            $data['received_via'] = $data['received_via'] ?? $received_via ?? null;
            $data['asset_component_id'] = isset($data['asset_component_id']) ? json_encode($data['asset_component_id']) : null;


            $create = Incident::create($data);

            $create_document = self::uploadDoc($data,$create);

            $create_resolution = self::createResolution($create->id);

            $create_workbasket = self::createWorkbasket($create->id);

            $create->refresh();

            $return = self::callAssetIncident($create);

            return self::generalResponse($return);
        }
        catch (\Throwable $th) {
            return self::error($th->getMessage());
        }
    }

    public static function update(Incident $incident,$data,$request){

        try{
            DB::beginTransaction();
        
            if($incident->code_sla !=  $data['code_sla']){
                $data['sla_version_id'] = self::getSlaVersion($incident);
                $data['expected_end_date'] = self::calculateDueDateIncident($incident);
            }

            $data['asset_component_id'] = isset($data['asset_component_id']) ? json_encode($data['asset_component_id']) : null;

            if($incident->status == Incident::CLOSED){
                $incident->workbasket?->delete();
                // self::calculatePenalty($incident);
            }

            $create = $incident->update($data);

            $create_document = self::uploadDoc($data,$incident);

            $return = self::callAssetIncident($incident);

            return self::generalResponse($return);
        }
        catch (\Throwable $th) {
            return self::error($th->getMessage());
        }
    }

    public static function view(Incident $incident){

        if (request()->source == 'workbasket') {
            $data_workbasket['status_complaint'] =  Workbasket::IN_PROGRESS;

            $resolution = $incident->incidentResolutionLatest;

            if(!$resolution->pickup_date){
                $resolution->pickup_date = Carbon::now();
                $resolution->save();
            }
            
            $incident->workbasket()->update($data_workbasket);
        }
        $data = new IncidentResources($incident);

        return self::success('Success', $data);
    }

    public static function delete($incident){

        if($incident->created_by != auth()->user()->id ){
            return self::error('Not user incident');
        }
        $incident->incidentResolution()->delete();

        $incident->delete();

        return self::success('Success', true);
    }

    public static function uploadDoc($data,Incident $incident){

        $appendix = isset($data['appendix_file']) ? $data['appendix_file'] : [];
        $asset = isset($data['asset_file']) ? $data['asset_file'] : [];

        self::createIncidentDocument($appendix,IncidentDocument::APPENDIX,$incident->id);
       
        self::createIncidentDocument($asset,IncidentDocument::ASSET,$incident->id);

        return true;
    }

    public static function createIncidentDocument($data,$document_type,$incident_id){

        if($document_type == IncidentDocument::APPENDIX){
            $folder = 'appendix';
        }
        else{
            $folder = 'asset';
        }

        $data_document['incident_id']  = $incident_id;

        $destination = storage_path('app/private/incident/'.$folder); 

        if (!file_exists($destination)) {
            mkdir($destination, 0777, true);
        }

        foreach($data as $document){
            if($document instanceof \Illuminate\Http\UploadedFile && $document->isValid()) {

                $mimeType = $document->getClientOriginalExtension();
                $file_name = time() . '_' . Str::random(10).'.'.$mimeType;

                $fileContents = file_get_contents($document->getRealPath());
        
                file_put_contents($destination . '/' . $file_name, $fileContents);
            
                $data_document['path'] = 'incident/'.$folder.'/'.$file_name;
                $data_document['type'] = $document_type;

                IncidentDocument::create($data_document);
            }
        }

        return true;
    }

    public static function createResolution($id){
        
        $data['incident_id'] = $id;
        $data['operation_user_id'] = 1;
        $data['action_codes'] = ActionCode::INITIAL;
    
        IncidentResolution::create($data);

        return true;
    }

    public static function createWorkbasket($id){
        $data['date'] = date('Y-m-d H:i:s');
        $data['incident_id'] = $id;
        $data['escalate_frontliner'] = true;

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

        $code = isset($data['code_sla']) ? $data['code_sla'] : null;

        $get_sla = Sla::where('code',$code)->first();

        $get_sla_details = SlaVersion::where('sla_template_id',$get_sla?->sla_template_id)->orderBy('version','desc')->first();

        return $get_sla_details?->id;
    }


    public static function getOperatingTime($branch_id){

        $operating_times = OperatingTime::select('day_start','day_end','operation_start','operation_end')
                                        ->where('branch_id', $branch_id)
                                        ->where('is_active',true)
                                        ->get();


        return $operating_times;
    }

    public static function getPublicHoliday($branch_id){
        $get_branch = Branch::select('state_id')->where('id',$branch_id)->first();
        $state_id = (int) $get_branch?->state_id;

        $calendar = Calendar::whereRaw("JSON_EXISTS(state_id, '$?(@ == $state_id)')")->get(['start_date', 'end_date']);

        $public_holidays = [];

        foreach ($calendar as $holiday) {
            $start = Carbon::parse($holiday->start_date);
            $end   = Carbon::parse($holiday->end_date);

            while ($start->lte($end)) {
                $public_holidays[] = $start->toDateString();
                $start->addDay();
            }
        }

        return $public_holidays;
    }

    public static function calculateDueDateIncident($data){

        $sla_version = isset($data['sla_version_id']) ? $data['sla_version_id'] : null;
        $incident_no = isset($data['incident_no']) ? $data['incident_no'] : null;

        $sla = SlaVersion::find($sla_version);
        
        if (!$sla) {
            return null;
        }

        $incident_date = $data['incident_date']->copy();
        $operating_times = self::getOperatingTime($data['branch_id']);
        $public_holidays = self::getPublicHoliday($data['branch_id']);

        $incident_date = self::shiftToNextWorkingPeriod($incident_date, $operating_times, $public_holidays);

        if ($sla->resolution_time_type == SlaTemplate::SLA_TYPE_DAY) {
            $due_date = self::calculateDueDateByDays($incident_date, (int)$sla->resolution_time, $operating_times, $public_holidays,$incident_no);
        }
        else{
            $sla_minutes = $sla->resolution_time_type == SlaTemplate::SLA_TYPE_HOUR ? (int)$sla->resolution_time * 60  : (int)$sla->resolution_time;

            $due_date =  self::calculateDueDateByMinutes($incident_date, $sla_minutes, $operating_times, $public_holidays,$incident_no);
        }

        return $due_date;
    }

    private static function calculateDueDateByDays($date, $sla_days, $operating_times, $public_holidays,$incident_no){
        $loop_guard = 0;
        $startTime = $date->format('H:i:s'); 
        $date = $date->copy()->addDay()->startOfDay();


        $logs[] = [
            'incident_no' => $incident_no,
            'type' => 'Days',
            'step' => 'start',
            'start_date' => $date->format('l, d F Y h:i A'),
            'sla_days' => $sla_days
        ];

        while ($sla_days > 0 && $loop_guard++ < 365){
            $date = self::shiftToNextWorkingPeriod($date, $operating_times, $public_holidays);

            $current_day = $date->isoWeekday();

            $period = $operating_times->first(function ($op) use ($current_day){
                return $op->day_start <= $current_day && $op->day_end >= $current_day;
            });

            if (!$period) {
                $logs[] = [
                    'step' => 'skip',
                    'day' => $date->format('l, d F Y'),
                    'note' => 'No operating period for this day'
                ];

                $date->addDay()->startOfDay();
                continue;
            }
            
            $end = $date->copy()->setTimeFromTimeString($period->operation_end);

            $sla_days--;

            $logs[] = [
                'step' => 'loop',
                'day' => $date->format('l, d F Y'),
                'period_end' => $end->format('h:i A'),
                'sla_days_remaining' => $sla_days
            ];

            if ($sla_days === 0){

                $logs[] = [
                    'step' => 'finished',
                    'final_due_date' => $end->format('l, d F Y h:i A')
                ];

                if($incident_no){
                    Log::channel('incident_details')->info('Due Date Calculation for incident Number : '.$incident_no, $logs);
                }

                $due = $date->copy()->setTimeFromTimeString($startTime);

                if ($due->between(
                    $date->copy()->setTimeFromTimeString($period->operation_start),
                    $date->copy()->setTimeFromTimeString($period->operation_end)
                )) {
                    return $due;
                }

                return $date->copy()->setTimeFromTimeString($period->operation_end);
            }

            $date = $date->copy()->setTimeFromTimeString($period->operation_end)->addSecond();
        }

        if($incident_no){
            Log::channel('incident_details')->info('Due Date Calculation (exhausted) for incident Number : '.$incident_no, $logs);
        }

        return $date;
    }

    private static function shiftToNextWorkingPeriod($date, $operating_times, $public_holidays){
        $loop_guard = 0;

        while ($loop_guard++ < 365) {
            $date_str = $date->toDateString();
            $current_day = $date->isoWeekday();

            if (in_array($date_str, $public_holidays)) {
                $date->addDay()->startOfDay();
                continue;
            }

            $period = $operating_times->first(function ($op) use ($current_day) {
                return $op->day_start <= $current_day && $op->day_end >= $current_day;
            });

            if (!$period) {
                $date->addDay()->startOfDay();
                continue;
            }

            $start = $date->copy()->setTimeFromTimeString($period->operation_start);
            $end   = $date->copy()->setTimeFromTimeString($period->operation_end);

            if ($date->lt($start)) return $start;
            if ($date->between($start, $end)) return $date;

            $date = $date->addDay()->startOfDay();
        }

        return $date; 
    }

    private static function calculateDueDateByMinutes($date, $sla_minutes, $operating_times, $public_holidays,$incident_no){
        $loop_guard = 0;

        $logs = [
            'incident_no' => $incident_no,
            'type' => 'minutes',
            'start_date' => $date->format('l, d F Y h:i A'),
            'sla_minutes' => $sla_minutes,
            'steps' => []
        ];

        while ($sla_minutes > 0 && $loop_guard++ < 365) {
            $date = self::shiftToNextWorkingPeriod($date, $operating_times, $public_holidays);

            $current_day = $date->isoWeekday();
            $period = $operating_times->first(function ($op) use ($current_day) {
                return $op->day_start <= $current_day && $op->day_end >= $current_day;
            });

            if (!$period) {

                $logs['steps'][] = [
                    'day' => $date->format('l, d F Y'),
                    'note' => 'No operating period, move to next day'
                ];

                $date->addDay()->startOfDay();
                continue;
            }

            $end = $date->copy()->setTimeFromTimeString($period->operation_end);
            $available = $date->diffInMinutes($end);

            $logs['steps'][] = [
                'day' => $date->format('l, d F Y'),
                'period_start' => $period->operation_start,
                'period_end'   => $period->operation_end,
                'available_minutes' => $available,
                'sla_remaining' => $sla_minutes
            ];

            if ($sla_minutes <= $available) {
                $final = $date->copy()->addMinutes($sla_minutes);

                $logs['final_due_date'] = $final->format('l, d F Y h:i A');

                if($incident_no){
                    Log::channel('incident_details')->info('Due Date Calculation for incident Number : '.$incident_no, $logs);
                }

                return $final;
            }

            $sla_minutes -= $available;
            $date = $end->copy()->addSecond();
        }

        $logs['final_due_date'] = $date->format('l, d F Y h:i A');
        $logs['note'] = 'Loop exhausted';

        if($incident_no){
            Log::channel('incident_details')->info('Due Date Calculation for incident Number : '.$incident_no, $logs);
        }

        return $date;
    }

    public static function checkPenalty(Incident $incident){
        $get_sla_version = $incident->slaVersion;
        $actual   = Carbon::parse($incident->actual_end_date);
        $expected = Carbon::parse($incident->expected_end_date);

        if ($get_sla_version->response_time_type == SlaTemplate::SLA_TYPE_MINUTE) {
            $total_sla_time = $expected->diffInMinutes($actual); 
        }
        elseif ($get_sla_version->response_time_type == SlaTemplate::SLA_TYPE_HOUR) {
            $total_sla_time = $expected->diffInHours($actual);
        }
        else {
            $total_sla_time = $expected->diffInDays($actual);
        }
        $interval_count = intdiv($total_sla_time, $get_sla_version->response_time);
        $penalty = $interval_count * $get_sla_version->response_time_penalty;
        // dd(,$interval_count,$total_sla_time,$get_sla_version->response_time,$interval_count,$penalty);

        $data_penalty['total_response_time_penalty_minute'] =  $total_sla_time;
        $data_penalty['total_response_time_penalty_price'] =  $penalty;
        $data_penalty['incident_id'] = $incident->id;

        $create = IncidentPenalty::create($data_penalty);
    }

    public static function downloadAssetFile($incident_no){

        $incident =  Incident::where('incident_no',$incident_no)->first();

        $documents = $incident?->incidentDocumentAsset;
        $documents = $incident?->incidentDocumentAppendix;

        if(!$documents) {
            return self::error('File not found');
        }

        $zip_file_name = 'incident_'.$incident->incident_no.'_documents.zip';
        $zip_path = storage_path('app/temp/'.$zip_file_name);

        if (!file_exists(dirname($zip_path))) {
            mkdir(dirname($zip_path), 0777, true);
        }

        $zip = new ZipArchive;

        if ($zip->open($zip_path, ZipArchive::CREATE | ZipArchive::OVERWRITE) === TRUE) {

            foreach ($documents as $doc) {
                $fullPath = storage_path('app/private/'.$doc->path);

                if (file_exists($fullPath)) {
                    $zip->addFile($fullPath, basename($fullPath));
                }
            }

            $zip->close();
        }

        return response()->download($zip_path)->deleteFileAfterSend(true);
        
    }

}


    
 