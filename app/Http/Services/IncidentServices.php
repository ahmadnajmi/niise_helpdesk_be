<?php

namespace App\Http\Services;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use App\Http\Traits\ResponseTrait;
use App\Http\Resources\IncidentResources;
use App\Events\WorkbasketUpdated;
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
    
    public static function create($data){

        try{
            $return = self::createIncident($data);

            return self::generalResponse($return);
        }
        catch (\Throwable $th) {
            return self::error($th->getMessage());
        }
    }

    public static function createIncident($data){
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

        $create_workbasket = self::createWorkbasket($create);
        $create->refresh();

        $return = self::callAssetIncident($create);

        return $return;
    }

    public static function update(Incident $incident,$data,$request){

        try{
            DB::beginTransaction();
        
            if($incident->code_sla !=  $data['code_sla'] || !$incident->sla_version_id){
                $data['incident_date'] = $incident->incident_date;
                $data['sla_version_id'] = self::getSlaVersion($data);
                $data['expected_end_date'] = self::calculateDueDateIncident($data);
            }

            $data['asset_component_id'] = isset($data['asset_component_id']) ? json_encode($data['asset_component_id']) : null;

            if($data['status'] == Incident::CLOSED){
                $incident->workbasket?->delete();
                $data['actual_end_date'] = Carbon::now();

                $trigger_workbasket = [
                    'frontliner' => false,
                    'contractor' => false,
                    'btmr' => true,
                    'jim' => true
                ];
                event(new WorkbasketUpdated($incident,$trigger_workbasket));

                self::generatePenalty($incident);
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
        $role = User::getUserRole(Auth::user()->id);
        $update_workbasket = false;
        $trigger_workbasket = [
            'frontliner' => false,
            'contractor' => false,
            'btmr' => false,
            'jim' => false
        ];

        if($role?->role == Role::FRONTLINER && $incident->workbasket?->escalate_frontliner){
            $update_workbasket = true;
            $trigger_workbasket['frontliner'] = true;
        }
        elseif($role?->role == Role::CONTRACTOR && !$incident->workbasket?->escalate_frontliner){
            $update_workbasket = true;
            $trigger_workbasket['contractor'] = true;
        }

        if (request()->source == 'workbasket' && $update_workbasket) {
            $data_workbasket['status_complaint'] =  Workbasket::IN_PROGRESS;
            $data_workbasket['status'] =  Workbasket::IN_PROGRESS;

            $resolution = $incident->incidentResolutionLatest;

            if(!$resolution->pickup_date){
                $resolution->pickup_date = Carbon::now();
                $resolution->save();
            }

            $role_created = User::getUserRole($incident->created_by);

            if($role_created?->role == Role::BTMR && $incident->workbasket?->status_complaint == Workbasket::NEW){
                $trigger_workbasket['btmr'] = true;
            }
            elseif($role_created?->role == Role::JIM && $incident->workbasket?->status_complaint == Workbasket::NEW){
                $trigger_workbasket['jim'] = true;
            }
            
            $incident->workbasket()->update($data_workbasket);

            event(new WorkbasketUpdated($incident,$trigger_workbasket));
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

    public static function generateEndDate($incident){
        $incident->expected_end_date = self::calculateDueDateIncident($incident);
        $incident->save();

        return self::success('Success', true);
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

    public static function uploadDoc($data,Incident $incident){

        $appendix = isset($data['appendix_file']) ? $data['appendix_file'] : [];
        $asset = isset($data['asset_file']) ? $data['asset_file'] : [];

        self::createIncidentDocument($appendix,IncidentDocument::APPENDIX,$incident->id);
       
        self::createIncidentDocument($asset,IncidentDocument::ASSET,$incident->id);

        return true;
    }

    public static function createIncidentDocument($data,$document_type,$incident_id){

        $folder = $document_type == IncidentDocument::APPENDIX ? 'appendix' : 'asset';

        $data_document['incident_id']  = $incident_id;

        $destination = storage_path('app/private/incident/'.$folder); 

        if (!file_exists($destination)) {
            mkdir($destination, 0777, true);
        }
        
        $disk = config('filesystems.default');

        foreach($data as $document){
            if($document instanceof \Illuminate\Http\UploadedFile && $document->isValid()) {

                $mimeType = $document->getClientOriginalExtension();
                $file_name = time() . '_' . Str::random(10).'.'.$mimeType;

                $path = 'incident/'.$folder.'/'.$file_name;

                Storage::disk($disk)->put(
                    $path,
                    file_get_contents($document->getRealPath())
                );

                $data_document['path'] = $path;
                $data_document['type'] = $document_type;

                IncidentDocument::create($data_document);
            }
        }

        return true;
    }

    public static function createResolution($id){
        
        $data['incident_id'] = $id;
        $data['action_codes'] = ActionCode::INITIAL;
    
        IncidentResolution::create($data);

        return true;
    }

    public static function createWorkbasket($incident){
        $data['date'] = date('Y-m-d H:i:s');
        $data['incident_id'] = $incident->id;
        $data['escalate_frontliner'] = true;

        Workbasket::create($data);

        $trigger_workbasket = [
            'frontliner' => true,
            'contractor' => false,
            'btmr' => false,
            'jim' => false
        ];

        if(auth()->check()){
            $role = User::getUserRole(Auth::user()->id);

            if($role?->role == Role::BTMR){
                $trigger_workbasket['btmr'] = true;
            }
            elseif($role?->role == Role::JIM){
                $trigger_workbasket['jim'] = true;
            }
        }

        event(new WorkbasketUpdated($incident,$trigger_workbasket));


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

    public static function generatePenalty(Incident $incident){

        $data_penalty = self::checkPenalty($incident);

        $check_penalty = IncidentPenalty::where('incident_id',$incident->id)->first();

        if($check_penalty){
            $update = $check_penalty->update($data_penalty);
        }
        else{
            $data_penalty['incident_id'] = $incident->id;

            IncidentPenalty::create($data_penalty);
        }

        return $data_penalty;
        
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

        $calendar = Calendar::where(function($query) use ($state_id) {
                                $query->whereRaw("JSON_EXISTS(state_id, '$?(@ == 0)')")
                                ->orWhereRaw("JSON_EXISTS(state_id, '$?(@ == $state_id)')");
                            })
                            ->get(['start_date', 'end_date']);

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

        $is24Hour = self::is24HourOperation($operating_times);

        $incident_date = self::shiftToNextWorkingPeriod($incident_date, $operating_times, $public_holidays,$is24Hour);

        if ($sla->resolution_time_type == SlaTemplate::SLA_TYPE_DAY) {
            $due_date = self::calculateDueDateByDays($incident_date, (int)$sla->resolution_time, $operating_times, $public_holidays,$incident_no,$is24Hour);
        }
        else if ($sla->resolution_time_type == SlaTemplate::SLA_TYPE_HOUR) {
            $total_hours = (int)$sla->resolution_time;
            
            if ($total_hours >= 24) {
                $days = floor($total_hours / 24);
                $remaining_hours = $total_hours % 24;
            
            Log::info('Converting hours to days', [
                'incident_no' => $incident_no,
                'total_hours' => $total_hours,
                'days' => $days,
                'remaining_hours' => $remaining_hours
            ]);
            
            $due_date = self::calculateDueDateByDays($incident_date, $days, $operating_times, $public_holidays, $incident_no, $is24Hour);
            
            if ($remaining_hours > 0) {
                $remaining_minutes = $remaining_hours * 60;
                $due_date = self::calculateDueDateByMinutes($due_date, $remaining_minutes, $operating_times, $public_holidays, $incident_no, $is24Hour);
            }
            } else {
                $sla_minutes = $total_hours * 60;
                $due_date = self::calculateDueDateByMinutes($incident_date, $sla_minutes, $operating_times, $public_holidays, $incident_no, $is24Hour);
            }
        }
        else {
            $sla_minutes = (int)$sla->resolution_time;
            $due_date = self::calculateDueDateByMinutes($incident_date, $sla_minutes, $operating_times, $public_holidays, $incident_no, $is24Hour);
        }

        return $due_date;
    }

    private static function isWithinOperatingDays($current_day, $day_start, $day_end) {
        // Handle normal range (e.g., Monday=1 to Friday=5)
        if ($day_start <= $day_end) {
            return $current_day >= $day_start && $current_day <= $day_end;
        }
        
        // Handle week wrap (e.g., Sunday=7 to Thursday=4)
        return $current_day >= $day_start || $current_day <= $day_end;
    }

    private static function isOvernightShift($operation_start, $operation_end) {
        return $operation_start > $operation_end;
    }

    private static function shiftToNextWorkingPeriod($date, $operating_times, $public_holidays,$is24Hour = false){
        $loop_guard = 0;

        while ($loop_guard++ < 365) {
            $date_str = $date->toDateString();
            $current_day = $date->isoWeekday();

            if (!$is24Hour && in_array($date_str, $public_holidays)) {
                $date->addDay()->startOfDay();
                continue;
            }

            $period = $operating_times->first(function ($op) use ($current_day) {
                return self::isWithinOperatingDays($current_day, $op->day_start, $op->day_end);
            });

            if (!$period) {
                $date->addDay()->startOfDay();
                continue;
            }

            $start = $date->copy()->setTimeFromTimeString($period->operation_start);
            $end   = $date->copy()->setTimeFromTimeString($period->operation_end);
            
            // Handle overnight shift (e.g., 16:00 to 01:00 next day)
            if (self::isOvernightShift($period->operation_start, $period->operation_end)) {
                $end->addDay();
            }

            if ($date->lt($start)) return $start;
            if ($date->lte($end)) return $date;

            $date = $date->addDay()->startOfDay();
        }

        return $date; 
    }

    private static function calculateDueDateByMinutes($date, $sla_minutes, $operating_times, $public_holidays, $incident_no,$is24Hour = false){
        $loop_guard = 0;

        $logs = [
            'incident_no' => $incident_no,
            'type' => 'minutes',
            'start_date' => $date->format('l, d F Y h:i A'),
            'sla_minutes' => $sla_minutes,
            'is_24_hour_operation' => $is24Hour,
            'steps' => []
        ];

        while ($sla_minutes > 0 && $loop_guard++ < 365) {
            $date = self::shiftToNextWorkingPeriod($date, $operating_times, $public_holidays,$is24Hour);

            $current_day = $date->isoWeekday();
            
            $period = $operating_times->first(function ($op) use ($current_day) {
                return self::isWithinOperatingDays($current_day, $op->day_start, $op->day_end);
            });

            if (!$period) {
                $logs['steps'][] = [
                    'day' => $date->format('l, d F Y'),
                    'note' => 'No operating period, move to next day'
                ];

                $date->addDay()->startOfDay();
                continue;
            }

            $start = $date->copy()->setTimeFromTimeString($period->operation_start);
            $end = $date->copy()->setTimeFromTimeString($period->operation_end);
            
            // Handle overnight shift
            if (self::isOvernightShift($period->operation_start, $period->operation_end)) {
                $end->addDay();
            }

            $available = $date->diffInMinutes($end);

            $logs['steps'][] = [
                'day' => $date->format('l, d F Y h:i A'),
                'period_start' => $period->operation_start,
                'period_end'   => $period->operation_end,
                'is_overnight' => self::isOvernightShift($period->operation_start, $period->operation_end),
                'end_datetime' => $end->format('l, d F Y h:i A'),
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

    private static function calculateDueDateByDays($date, $sla_days, $operating_times, $public_holidays, $incident_no, $is24Hour = false){
        $loop_guard = 0;
        $startTime = $date->format('H:i:s'); // Save original incident time (12:18:00)
        
        $logs[] = [
            'incident_no' => $incident_no,
            'type' => 'Days',
            'step' => 'start',
            'start_date' => $date->format('l, d F Y h:i A'),
            'original_time' => $startTime,
            'is_24_hour_operation' => $is24Hour,
            'sla_days' => $sla_days
        ];

        // Count the days WITHOUT moving to next day first
        while ($sla_days > 0 && $loop_guard++ < 365){
            // Move to next day for counting
            $date = $date->copy()->addDay()->startOfDay();
            $date = self::shiftToNextWorkingPeriod($date, $operating_times, $public_holidays, $is24Hour);

            $current_day = $date->isoWeekday();

            $period = $operating_times->first(function ($op) use ($current_day){
                return self::isWithinOperatingDays($current_day, $op->day_start, $op->day_end);
            });

            if (!$period) {
                $logs[] = [
                    'step' => 'skip',
                    'day' => $date->format('l, d F Y'),
                    'note' => 'No operating period for this day'
                ];
                continue;
            }

            $sla_days--;

            $logs[] = [
                'step' => 'loop',
                'day' => $date->format('l, d F Y'),
                'sla_days_remaining' => $sla_days
            ];
        }

        // Now set the time back to the original incident time
        $due = $date->copy()->setTimeFromTimeString($startTime);

        // Check if the original time falls within operating hours
        $opStart = $date->copy()->setTimeFromTimeString($period->operation_start);
        $opEnd = $date->copy()->setTimeFromTimeString($period->operation_end);
        
        if (self::isOvernightShift($period->operation_start, $period->operation_end)) {
            $opEnd->addDay();
        }

        // If original time is BEFORE operating hours, move to operation start
        if ($due->lt($opStart)) {
            $due = $opStart->copy();
        }
        // If original time is AFTER operating hours, keep it as is (will be handled by minutes calculation)
        // Don't force it to operation end!

        $logs[] = [
            'step' => 'finished',
            'final_date' => $date->format('l, d F Y'),
            'original_time' => $startTime,
            'final_due_date' => $due->format('l, d F Y h:i A')
        ];

        if($incident_no){
            Log::channel('incident_details')->info('Due Date Calculation for incident Number : '.$incident_no, $logs);
        }

        return $due;
    }

    private static function is24HourOperation($operating_times) {
        foreach ($operating_times as $op) {
            if ($op->operation_start === '00:00' && 
                (in_array($op->operation_end, ['23:59', '23:59:59']))) {
                return true;
            }
        }
        return false;
    }

    public static function checkPenalty(Incident $incident){

        $get_sla_version = $incident->slaVersion;

        $data['penalty_irt'] = self::penaltyInitialResponseTime($incident,$get_sla_version);
        $data['penalty_ort'] = self::penaltyOnSiteResponseTime($incident,$get_sla_version);
        $data['penalty_prt'] = self::penaltyProblemResolutionTime($incident,$get_sla_version);
        $data['penalty_vprt'] = self::penaltyVerifyProblemResolutionTime($incident,$get_sla_version);

        return $data;        
    }

    private static function penaltyInitialResponseTime($incident, $get_sla_version){
        $penalty_irt = 0;

        $get_init = IncidentResolution::where('incident_id', $incident->id)
                                        ->where('action_codes', ActionCode::INITIAL)
                                        ->first();

        $get_escalate = IncidentResolution::where('incident_id', $incident->id)
                                        ->where('action_codes', ActionCode::ESCALATE)
                                        ->orderBy('created_at', 'asc')
                                        ->first();

        if($get_init && $get_escalate && $get_sla_version){
            $start_date = Carbon::parse($get_init->created_at);
            $end_date = Carbon::parse($get_escalate->created_at);

            // $penalty_irt = self::formulaCalculation($start_date,$end_date,$get_sla_version,'irt');
            $penalty_irt = self::formulaCalculationWithOperatingHours(
                $start_date,
                $end_date,
                $get_sla_version,
                'irt',
                $incident->branch_id
            );
        }

        return $penalty_irt;
    }

    private static function penaltyOnSiteResponseTime($incident, $get_sla_version){
        $penalty_ort = 0;

        $get_onsite = IncidentResolution::where('incident_id', $incident->id)
                                        ->where('action_codes', ActionCode::ONSITE)
                                        ->first();
        
        $get_escalate_contractor = IncidentResolution::where('incident_id', $incident->id)
                                        ->where('action_codes', ActionCode::ESCALATE)
                                        ->where('group_id', $incident->assign_group_id)
                                        ->first();

        if($get_onsite && $get_sla_version && $get_escalate_contractor){
            $start_date = Carbon::parse($get_escalate_contractor->created_at);
            $end_date = Carbon::parse($get_onsite->created_at);

        // $penalty_ort = self::formulaCalculation($start_date,$end_date,$get_sla_version,'ort');
            $penalty_ort = self::formulaCalculationWithOperatingHours(
                $start_date,
                $end_date,
                $get_sla_version,
                'ort',
                $incident->branch_id
            );
        }

        return $penalty_ort;
    }

    private static function penaltyProblemResolutionTime($incident,$get_sla_version){
        $penalty_prt = 0;

        $end_date = IncidentResolution::where('incident_id', $incident->id)
                                        ->where('action_codes', ActionCode::ACTR)
                                        ->first();
        
        $start_date = IncidentResolution::where('incident_id', $incident->id)
                                        ->where('action_codes', ActionCode::ESCALATE)
                                        ->where('group_id', $incident->assign_group_id)
                                        ->first();

        if($end_date && $start_date && $get_sla_version){
            $start_date = Carbon::parse($start_date->created_at);
            $end_date = Carbon::parse($end_date->created_at);

            // $penalty_prt = self::formulaCalculation($start_date,$end_date,$get_sla_version,'prt');
            $penalty_prt = self::formulaCalculationWithOperatingHours(
                $start_date,
                $end_date,
                $get_sla_version,
                'prt',
                $incident->branch_id
            );
        }


        return $penalty_prt;
        
    }

    private static function penaltyVerifyProblemResolutionTime($incident,$get_sla_version){
        $penalty_vprt = 0;

        $start_date = IncidentResolution::where('incident_id', $incident->id)
                                        ->where('action_codes', ActionCode::ACTR)
                                        ->first();
        
        $end_date = IncidentResolution::where('incident_id', $incident->id)
                                        ->where('action_codes', ActionCode::VERIFY)
                                        ->first();

        if($end_date && $start_date && $get_sla_version){
            $start_date = Carbon::parse($start_date->created_at);
            $end_date = Carbon::parse($end_date->created_at);

            // $penalty_vprt = self::formulaCalculation($start_date,$end_date,$get_sla_version,'vprt');
            $penalty_vprt = self::formulaCalculationWithOperatingHours(
                $start_date,
                $end_date,
                $get_sla_version,
                'vprt',
                $incident->branch_id
            );
        }


        return $penalty_vprt;
        
    }

    // private static function formulaCalculationWithOperatingHours($start_date, $end_date, $sla_version, $type, $branch_id){
    //     $get_penalty_details = self::penaltyDetails($sla_version, $type);

    //     if (collect($get_penalty_details)->contains(fn ($v) => $v === null || $v === '')) {
    //         return 0;
    //     }

    //     $operating_times = self::getOperatingTime($branch_id);
    //     $public_holidays = self::getPublicHoliday($branch_id);
    //     $is24Hour = self::is24HourOperation($operating_times);

    //     $actualSeconds = self::calculateWorkingSeconds($start_date, $end_date, $operating_times, $public_holidays, $is24Hour);

    //     $time_type = (int) $get_penalty_details['time_type'];

    //     $slaLimitSeconds = match ($time_type) {
    //         SlaTemplate::SLA_TYPE_MINUTE => $get_penalty_details['time'] * 60,
    //         SlaTemplate::SLA_TYPE_HOUR   => $get_penalty_details['time'] * 3600,
    //         SlaTemplate::SLA_TYPE_DAY    => $get_penalty_details['time'] * 86400,
    //     };

    //     $lateSeconds = max(0, $actualSeconds - $slaLimitSeconds);
    //     if($type == 'vprt')dd($start_date,$end_date,$operating_times);
    //     if ($lateSeconds === 0) {
    //         return 0;
    //     }

    //     $penalty_time_type = (int) $get_penalty_details['penalty_type'];

    //     $penaltyUnitSeconds = match ($penalty_time_type) {
    //         SlaTemplate::SLA_TYPE_MINUTE => 60,
    //         SlaTemplate::SLA_TYPE_HOUR   => 3600,
    //         SlaTemplate::SLA_TYPE_DAY    => 86400,
    //     };

    //     $lateUnits = $lateSeconds / $penaltyUnitSeconds;

    //     $penalty_price = round($lateUnits * $get_penalty_details['penalty'], 2);

    //     return $penalty_price;
    // }

    // private static function calculateWorkingSeconds($start_date, $end_date, $operating_times, $public_holidays, $is24Hour = false){
    //     $totalSeconds = 0;
    //     $current = $start_date->copy();
    //     $loop_guard = 0;

    //     while ($current->lt($end_date) && $loop_guard++ < 365) {
    //         $date_str = $current->toDateString();
    //         $current_day = $current->isoWeekday();

    //         if (!$is24Hour && in_array($date_str, $public_holidays)) {
    //             $current->addDay()->startOfDay();
    //             continue;
    //         }

    //         $period = $operating_times->first(function ($op) use ($current_day) {
    //             return self::isWithinOperatingDays($current_day, $op->day_start, $op->day_end);
    //         });

    //         if (!$period) {
    //             $current->addDay()->startOfDay();
    //             continue;
    //         }

    //         $opStart = $current->copy()->setTimeFromTimeString($period->operation_start);
    //         $opEnd = $current->copy()->setTimeFromTimeString($period->operation_end);

    //         if (self::isOvernightShift($period->operation_start, $period->operation_end)) {
    //             $opEnd->addDay();
    //         }

    //         $periodStart = $current->lt($opStart) ? $opStart : $current;
    //         $periodEnd = $end_date->lt($opEnd) ? $end_date : $opEnd;

    //         if ($periodStart->lt($periodEnd)) {
    //             $totalSeconds += $periodStart->diffInSeconds($periodEnd);
    //         }

    //         if ($end_date->gt($opEnd)) {
    //             $current = $opEnd->copy()->addSecond();

    //             if ($current->format('H:i:s') !== '00:00:00' && $current->gt($opEnd)) {
    //                 $current->startOfDay();
    //             }
    //         } else {
    //             break;
    //         }
    //     }

    //     return $totalSeconds;
    // }

    // private static function formulaCalculation($start_date,$end_date,$sla_version,$type){

    //     $get_penalty_details = self::penaltyDetails($sla_version,$type);

    //     if (collect($get_penalty_details)->contains(fn ($v) => $v === null || $v === '')) {
    //         return 0;
    //     }

    //     $actualSeconds = $start_date->diffInSeconds($end_date);

    //     $type = (int) $get_penalty_details['time_type'];

    //     $slaLimitSeconds = match ($type) {
    //         SlaTemplate::SLA_TYPE_MINUTE => $get_penalty_details['time'] * 60,
    //         SlaTemplate::SLA_TYPE_HOUR   => $get_penalty_details['time'] * 3600,
    //         SlaTemplate::SLA_TYPE_DAY    => $get_penalty_details['time'] * 86400,
    //     };

    //     $lateSeconds = max(0, $actualSeconds - $slaLimitSeconds);
        
    //     if ($lateSeconds === 0) {
    //         return 0;
    //     }

    //     $penalty_time_type = (int) $get_penalty_details['penalty_type'];

    //     $penaltyUnitSeconds = match ($penalty_time_type) {
    //         SlaTemplate::SLA_TYPE_MINUTE => 60,
    //         SlaTemplate::SLA_TYPE_HOUR   => 3600,
    //         SlaTemplate::SLA_TYPE_DAY    => 86400,
    //     };

    //     $lateUnits = $lateSeconds / $penaltyUnitSeconds;

    //     $penalty_price = round($lateUnits * $get_penalty_details['penalty'], 2);

    //     return $penalty_price;
    // }

    private static function penaltyDetails($get_sla_version,$type){
        $data = [
            'time' => null,
            'time_type' => null,
            'penalty' => null,
            'penalty_type' => null
        ];

        if($type == 'irt'){
            $data['time'] = $get_sla_version->response_time;
            $data['time_type'] = $get_sla_version->response_time_type;
            $data['penalty'] = $get_sla_version->response_time_penalty;
            $data['penalty_type'] = $get_sla_version->response_time_penalty_type; 
        }
        elseif($type == 'prt'){

            $data['time'] = $get_sla_version->resolution_time;
            $data['time_type'] = $get_sla_version->resolution_time_type;
            $data['penalty'] = $get_sla_version->resolution_time_penalty;
            $data['penalty_type'] = $get_sla_version->resolution_time_penalty_type; 
        }
        elseif($type == 'ort'){
            $data['time'] = $get_sla_version->response_time_location;
            $data['time_type'] = $get_sla_version->response_time_location_type;
            $data['penalty'] = $get_sla_version->response_time_location_penalty;
            $data['penalty_type'] = $get_sla_version->response_time_location_penalty_type; 
        }
        elseif($type == 'vprt'){
            $data['time'] = $get_sla_version->verify_resolution_time;
            $data['time_type'] = $get_sla_version->verify_resolution_time_type;
            $data['penalty'] = $get_sla_version->verify_resolution_time_penalty;
            $data['penalty_type'] = $get_sla_version->verify_resolution_time_penalty_type; 

        }

       
        return $data;
    }





    // private static function formulaCalculationWithOperatingHours($start_date, $end_date, $sla_version, $type, $branch_id){
    //     $get_penalty_details = self::penaltyDetails($sla_version, $type);

    //     if (collect($get_penalty_details)->contains(fn ($v) => $v === null || $v === '')) {
    //         return 0;
    //     }

    //     // Ignore seconds - set to 00
    //     $start_date = $start_date->copy()->setSeconds(0);
    //     $end_date = $end_date->copy()->setSeconds(0);

    //     // Get operating times and holidays
    //     $operating_times = self::getOperatingTime($branch_id);
    //     $public_holidays = self::getPublicHoliday($branch_id);
    //     $is24Hour = self::is24HourOperation($operating_times);

    //     // Calculate actual working seconds between start and end date
    //     $actualSeconds = self::calculateWorkingSeconds($start_date, $end_date, $operating_times, $public_holidays, $is24Hour);

    //     Log::info('Penalty Calculation Debug', [
    //         'type' => $type,
    //         'start_date' => $start_date->format('Y-m-d H:i:s'),
    //         'end_date' => $end_date->format('Y-m-d H:i:s'),
    //         'actual_working_seconds' => $actualSeconds,
    //         'actual_working_minutes' => round($actualSeconds / 60, 2),
    //         'penalty_details' => $get_penalty_details
    //     ]);

    //     $time_type = (int) $get_penalty_details['time_type'];

    //     $slaLimitSeconds = match ($time_type) {
    //         SlaTemplate::SLA_TYPE_MINUTE => $get_penalty_details['time'] * 60,
    //         SlaTemplate::SLA_TYPE_HOUR   => $get_penalty_details['time'] * 3600,
    //         SlaTemplate::SLA_TYPE_DAY    => $get_penalty_details['time'] * 86400,
    //     };

    //     $lateSeconds = max(0, $actualSeconds - $slaLimitSeconds);
        
    //     Log::info('Penalty Calculation Result', [
    //         'type' => $type,
    //         'sla_limit_seconds' => $slaLimitSeconds,
    //         'late_seconds' => $lateSeconds,
    //         'late_minutes' => round($lateSeconds / 60, 2)
    //     ]);
        
    //     if ($lateSeconds === 0) {
    //         return 0;
    //     }

    //     $penalty_time_type = (int) $get_penalty_details['penalty_type'];

    //     $penaltyUnitSeconds = match ($penalty_time_type) {
    //         SlaTemplate::SLA_TYPE_MINUTE => 60,
    //         SlaTemplate::SLA_TYPE_HOUR   => 3600,
    //         SlaTemplate::SLA_TYPE_DAY    => 86400,
    //     };

    //     $lateUnits = $lateSeconds / $penaltyUnitSeconds;

    //     $penalty_price = round($lateUnits * $get_penalty_details['penalty'], 2);

    //     Log::info('Final Penalty', [
    //         'type' => $type,
    //         'late_units' => $lateUnits,
    //         'penalty_per_unit' => $get_penalty_details['penalty'],
    //         'total_penalty' => $penalty_price
    //     ]);

    //     return $penalty_price;
    // }

    // private static function calculateWorkingSeconds($start_date, $end_date, $operating_times, $public_holidays, $is24Hour = false){
    //     $totalSeconds = 0;
    //     $current = $start_date->copy();
    //     $loop_guard = 0;

    //     Log::info('calculateWorkingSeconds - Start', [
    //         'start_date' => $start_date->format('Y-m-d H:i:s'),
    //         'end_date' => $end_date->format('Y-m-d H:i:s'),
    //         'is_24_hour' => $is24Hour
    //     ]);

    //     while ($current->lt($end_date) && $loop_guard++ < 365) {
    //         $date_str = $current->toDateString();
    //         $current_day = $current->isoWeekday();

    //         // Skip public holidays for non-24-hour operations
    //         if (!$is24Hour && in_array($date_str, $public_holidays)) {
    //             Log::info('Skipping public holiday', ['date' => $date_str]);
    //             $current->addDay()->startOfDay();
    //             continue;
    //         }

    //         // Find the operating period for this day
    //         $period = $operating_times->first(function ($op) use ($current_day) {
    //             return self::isWithinOperatingDays($current_day, $op->day_start, $op->day_end);
    //         });

    //         // Skip non-operating days
    //         if (!$period) {
    //             Log::info('No operating period', ['date' => $date_str, 'day' => $current_day]);
    //             $current->addDay()->startOfDay();
    //             continue;
    //         }

    //         $opStart = $current->copy()->setTimeFromTimeString($period->operation_start);
    //         $opEnd = $current->copy()->setTimeFromTimeString($period->operation_end);

    //         // Handle overnight shift
    //         if (self::isOvernightShift($period->operation_start, $period->operation_end)) {
    //             $opEnd->addDay();
    //         }

    //         // Determine the effective start and end times for this period
    //         $periodStart = $current->lt($opStart) ? $opStart : $current;
    //         $periodEnd = $end_date->lt($opEnd) ? $end_date : $opEnd;

    //         // If the period start is before the period end, add the seconds
    //         if ($periodStart->lt($periodEnd)) {
    //             $secondsInPeriod = $periodStart->diffInSeconds($periodEnd);
    //             $totalSeconds += $secondsInPeriod;
                
    //             Log::info('Adding working time', [
    //                 'date' => $date_str,
    //                 'period_start' => $periodStart->format('Y-m-d H:i:s'),
    //                 'period_end' => $periodEnd->format('Y-m-d H:i:s'),
    //                 'seconds_added' => $secondsInPeriod,
    //                 'total_so_far' => $totalSeconds
    //             ]);
    //         }

    //         // Move to the start of next day
    //         $nextDay = $current->copy()->addDay()->startOfDay();
            
    //         // If end_date is still after the next day start, continue loop
    //         if ($end_date->gt($nextDay)) {
    //             $current = $nextDay;
    //         } else {
    //             // We're done
    //             break;
    //         }
    //     }

    //     Log::info('calculateWorkingSeconds - End', [
    //         'total_seconds' => $totalSeconds,
    //         'total_minutes' => round($totalSeconds / 60, 2),
    //         'total_hours' => round($totalSeconds / 3600, 2)
    //     ]);

    //     return $totalSeconds;
    // }




    private static function formulaCalculationWithOperatingHours($start_date, $end_date, $sla_version, $type, $branch_id){
        $get_penalty_details = self::penaltyDetails($sla_version, $type);

        if (collect($get_penalty_details)->contains(fn ($v) => $v === null || $v === '')) {
            return 0;
        }

        // Store original dates for logging
        $original_start = $start_date->format('Y-m-d H:i:s');
        $original_end = $end_date->format('Y-m-d H:i:s');

        // Ignore seconds - set to 00
        $start_date = $start_date->copy()->setSeconds(0);
        $end_date = $end_date->copy()->setSeconds(0);

        // Check if dates are in wrong order (end before start means data issue)
        if ($end_date->lte($start_date)) {
            Log::warning('Invalid date order in penalty calculation', [
                'type' => $type,
                'original_start' => $original_start,
                'original_end' => $original_end,
                'start_after_seconds_removed' => $start_date->format('Y-m-d H:i:s'),
                'end_after_seconds_removed' => $end_date->format('Y-m-d H:i:s')
            ]);
            return 0;
        }

        // Get operating times and holidays
        $operating_times = self::getOperatingTime($branch_id);
        $public_holidays = self::getPublicHoliday($branch_id);
        $is24Hour = self::is24HourOperation($operating_times);

        // Calculate actual working seconds between start and end date
        $actualSeconds = self::calculateWorkingSeconds($start_date, $end_date, $operating_times, $public_holidays, $is24Hour);

        Log::info('Penalty Calculation Debug', [
            'type' => $type,
            'original_start' => $original_start,
            'original_end' => $original_end,
            'start_date' => $start_date->format('Y-m-d H:i:s'),
            'end_date' => $end_date->format('Y-m-d H:i:s'),
            'actual_working_seconds' => $actualSeconds,
            'actual_working_minutes' => round($actualSeconds / 60, 2),
            'penalty_details' => $get_penalty_details
        ]);

        $time_type = (int) $get_penalty_details['time_type'];

        $slaLimitSeconds = match ($time_type) {
            SlaTemplate::SLA_TYPE_MINUTE => $get_penalty_details['time'] * 60,
            SlaTemplate::SLA_TYPE_HOUR   => $get_penalty_details['time'] * 3600,
            SlaTemplate::SLA_TYPE_DAY    => $get_penalty_details['time'] * 86400,
        };

        $lateSeconds = max(0, $actualSeconds - $slaLimitSeconds);
        
        Log::info('Penalty Calculation Result', [
            'type' => $type,
            'sla_limit_seconds' => $slaLimitSeconds,
            'late_seconds' => $lateSeconds,
            'late_minutes' => round($lateSeconds / 60, 2)
        ]);
        
        if ($lateSeconds === 0) {
            return 0;
        }

        $penalty_time_type = (int) $get_penalty_details['penalty_type'];

        $penaltyUnitSeconds = match ($penalty_time_type) {
            SlaTemplate::SLA_TYPE_MINUTE => 60,
            SlaTemplate::SLA_TYPE_HOUR   => 3600,
            SlaTemplate::SLA_TYPE_DAY    => 86400,
        };

        $lateUnits = $lateSeconds / $penaltyUnitSeconds;

        $penalty_price = round($lateUnits * $get_penalty_details['penalty'], 2);

        Log::info('Final Penalty', [
            'type' => $type,
            'late_units' => $lateUnits,
            'penalty_per_unit' => $get_penalty_details['penalty'],
            'total_penalty' => $penalty_price
        ]);

        return $penalty_price;
    }

    private static function calculateWorkingSeconds($start_date, $end_date, $operating_times, $public_holidays, $is24Hour = false){
        $totalSeconds = 0;
        $current = $start_date->copy();
        $loop_guard = 0;

        Log::info('calculateWorkingSeconds - Start', [
            'start_date' => $start_date->format('Y-m-d H:i:s'),
            'end_date' => $end_date->format('Y-m-d H:i:s'),
            'is_24_hour' => $is24Hour
        ]);

        while ($current->lt($end_date) && $loop_guard++ < 365) {
            $date_str = $current->toDateString();
            $current_day = $current->isoWeekday();

            // Skip public holidays for non-24-hour operations
            if (!$is24Hour && in_array($date_str, $public_holidays)) {
                Log::info('Skipping public holiday', ['date' => $date_str]);
                $current->addDay()->startOfDay();
                continue;
            }

            // Find the operating period for this day
            $period = $operating_times->first(function ($op) use ($current_day) {
                return self::isWithinOperatingDays($current_day, $op->day_start, $op->day_end);
            });

            // Skip non-operating days
            if (!$period) {
                Log::info('No operating period', ['date' => $date_str, 'day' => $current_day]);
                $current->addDay()->startOfDay();
                continue;
            }

            $opStart = $current->copy()->setTimeFromTimeString($period->operation_start);
            $opEnd = $current->copy()->setTimeFromTimeString($period->operation_end);

            // Handle overnight shift
            if (self::isOvernightShift($period->operation_start, $period->operation_end)) {
                $opEnd->addDay();
            }

            // Determine the effective start and end times for this period
            $periodStart = $current->lt($opStart) ? $opStart : $current;
            $periodEnd = $end_date->lt($opEnd) ? $end_date : $opEnd;

            // If the period start is before the period end, add the seconds
            if ($periodStart->lt($periodEnd)) {
                $secondsInPeriod = $periodStart->diffInSeconds($periodEnd);
                $totalSeconds += $secondsInPeriod;
                
                Log::info('Adding working time', [
                    'date' => $date_str,
                    'period_start' => $periodStart->format('Y-m-d H:i:s'),
                    'period_end' => $periodEnd->format('Y-m-d H:i:s'),
                    'seconds_added' => $secondsInPeriod,
                    'total_so_far' => $totalSeconds
                ]);
            }

            // Move to the start of next day
            $nextDay = $current->copy()->addDay()->startOfDay();
            
            // If end_date is still after the next day start, continue loop
            if ($end_date->gt($nextDay)) {
                $current = $nextDay;
            } else {
                // We're done
                break;
            }
        }

        Log::info('calculateWorkingSeconds - End', [
            'total_seconds' => $totalSeconds,
            'total_minutes' => round($totalSeconds / 60, 2),
            'total_hours' => round($totalSeconds / 3600, 2)
        ]);

        return $totalSeconds;
    }
}


    
 