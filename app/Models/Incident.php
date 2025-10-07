<?php

namespace App\Models;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Facades\Auth;
use DB;

class Incident extends BaseModel
{
    use HasFactory;
    protected $table = 'incidents';

    protected $fillable = [ 
        'incident_no',
        'code_sla',
        'incident_date',
        'barcode',
        'branch_id',
        'category_id',
        'complaint_id',
        'complaint_user_id',
        'information',
        'knowledge_base_id',
        'received_via',
        'report_no',
        'incident_asset_type',
        'date_asset_loss',
        'date_report_police',
        'report_police_no',
        'asset_siri_no',
        'group_id',
        'operation_user_id',
        'appendix_file',
        'asset_file',
        'expected_end_date',
        'actual_end_date',
        'status',
        'asset_parent_id',
        'asset_component_id',
        'sla_version_id',
        'service_recipient_id',
        'resolved_user_id',
    ];

    protected $casts = [
        'incident_date' => 'datetime:Y-m-d',
        'date_asset_loss' => 'datetime:Y-m-d',
        'date_report_police' => 'datetime:Y-m-d',
        'expected_end_date' => 'datetime:Y-m-d',
        'actual_end_date' => 'datetime:Y-m-d',
    ];

    const OPEN = 1;
    const RESOLVED = 2;
    const CLOSED = 3;
    const CANCEL_DUPLICATE = 4;
    const ON_HOLD = 5;
    
    const RECIEVED_PHONE = 1;
    const RECIEVED_EMAIL = 2;
    const RECIEVED_CHATBOT = 3;
    const RECIEVED_LIVECHAT = 4;
    const RECIEVED_SYSTEM = 5;

    protected static function booted(){
        static::creating(function ($model) {
            $model->incident_no = self::generateIncidentNo();
        });
    }

    public static function generateIncidentNo(){
        $get_incident = self::orderBy('incident_no','desc')->first();

        if($get_incident){
            $code = $get_incident->incident_no;
            $old_code = substr($code, -5);
            $incremented = (int)$old_code + 1;
            $next_number = str_pad($incremented, 5, '0', STR_PAD_LEFT);
        } else {
            $next_number = '00001';
        }

        return 'TN'.date('Ymd').$next_number;
    }

    public function branch(){
        return $this->hasOne(Branch::class,'id','branch_id');
    }

    public function receviedViaDescription(){
        return $this->hasOne(RefTable::class,'ref_code','received_via')->where('code_category', 'received_via');
    }

    public function incidentAssetTypeDescription(){
        return $this->hasOne(RefTable::class,'ref_code','incident_asset_type')->where('code_category', 'incident_asset_type');
    }

    public function complaint(){
        return $this->hasOne(Complaint::class,'id','complaint_id');
    }

    public function complaintUser(){
        return $this->hasOne(User::class,'id','complaint_user_id');
    }

    public function resolvedByUser(){
        return $this->hasOne(User::class,'id','resolved_user_id');
    }

    public function slaVersion(){
        return $this->hasOne(SlaVersion::class,'id','sla_version_id');
    }

    public function sla(){
        return $this->hasOne(Sla::class,'code','code_sla');
    }

    public function incidentResolution(){
        return $this->hasMany(IncidentResolution::class, 'incident_id','id')->orderBy('created_at','asc');
    }

    public function categoryDescription(){
        return $this->hasOne(Category::class,'id','category_id');
    }

    public function group(){
        return $this->hasOne(Group::class, 'id','group_id');
    }
    
    public function operationUser(){
        return $this->hasOne(User::class,'id','operation_user_id');
    }

    public function serviceRecipient(){
        return $this->hasOne(User::class,'id','service_recipient_id');
    }

    public function statusDesc(){
        return $this->hasOne(RefTable::class,'ref_code','status')->where('code_category', 'incident_status');
    }

    public function workbasket(){
        return $this->hasOne(Workbasket::class,'incident_id','id');
    }

    protected function calculateCountDownSettlement(): Attribute{
        return Attribute::get(function () {

            if(!$this->actual_end_date){
                $diff = $this->incident_date->diff($this->expected_end_date);

                return $diff->d .' Hari : ' . $diff->h . ' Jam : ' .$diff->i  .' Minit';
            }
            else{
                return '00 Hari : 00 Jam : 00 Minit';
            }
        });
    }

    protected function calculateBreachTime(): Attribute{
        return Attribute::get(function () {

            if (!$this->actual_end_date || $this->actual_end_date->lessThanOrEqualTo($this->expected_end_date)) {
                return '00 Hari : 00 Jam : 00 Minit';
            }
            else{
                $diff = $this->expected_end_date->diff($this->actual_end_date);

                return $diff->d .' Hari : ' . $diff->h . ' Jam : ' .$diff->i  .' Minit';
            }
        });
    }

    public static  function getIdleIncidents(){
        $data = Incident::select('b.name as customer_name', DB::raw('COUNT(*) as total_logs'))
                        ->join('branch as b', 'incidents.branch_id', '=', 'b.id')
                        ->whereNotIn('incidents.status', [3, 4])
                        ->where('incidents.updated_at', '<=', now()->subDays(2)) // Incident itself not updated
                        ->groupBy('b.name')
                        ->get();

        return $data->map(function ($item) {
                return [
                    'CUSTNAME' => $item->customer_name,
                    'TOTLOGS'  => $item->total_logs,
                ];
            })->toArray();
                        
    }

    public static function filterIncident($request){
        $limit = $request->limit ? $request->limit : 15;

        $role = User::getUserRole(Auth::user()->id);

        $group_id = UserGroup::where('user_id',Auth::user()->id)->pluck('groups_id');

        $data =  Incident::when($role?->role == Role::JIM, function ($query){
                            $query->where('created_by',Auth::user()->id);
                        })
                        ->when($role?->role == Role::CONTRACTOR, function ($query)use($group_id){
                            return $query->whereHas('incidentResolution', function ($query)use($group_id) {
                                $query->whereIn('group_id',$group_id); 
                            });
                        })
                        ->when($request->status, function ($query) use ($request) {
                            if (is_array($request->status)) {
                                return $query->whereIn('status', $request->status);
                            }
                            return $query->where('status', $request->status);
                        })
                        ->when($request->type == 'more_4_day', function ($query) use ($request) {
                            return $query->where('status',Incident::OPEN)
                                        ->where('incident_date', '<', now()->startOfDay()->modify('-4 days'));
                        })
                        ->when($request->type == '4_day', function ($query) use ($request) {
                            return $query->where('status',Incident::OPEN)
                                        ->where('incident_date', now()->startOfDay()->modify('-4 days'));
                        })
                        ->when($request->type == 'less_4_day', function ($query) use ($request) {
                            return $query->where('status',Incident::OPEN)
                                        ->where('incident_date', '>', now()->startOfDay()->modify('-4 days'));
                        })
                        ->when($request->type == 'tbb', function ($query) use ($request) {
                            return $query->whereIn('status',[Incident::OPEN,Incident::ON_HOLD])
                                        ->whereBetween('expected_end_date', [
                                            now()->startOfDay(),      
                                            now()->addDays(2)->endOfDay()   
                                        ]);
                        })
                        ->when($request->branch_id, function ($query) use ($request) {
                            return $query->where('branch_id',$request->branch_id);
                        })
                        ->when($request->category_id, function ($query) use ($request) {
                            return $query->whereHas('categoryDescription', function ($query)use($request) {
                                    $query->where('id',$request->category_id); 
                            });
                        })
                        ->when($request->main_category_id, function ($query) use ($request) {
                            return $query->whereHas('categoryDescription', function ($query)use($request) {
                                    $query->where('category_id',$request->main_category_id)
                                        ->orWhere('id',$request->main_category_id); 
                            });
                        })
                        
                        ->when($request->severity_id, function ($query) use ($request) {
                            return $query->whereHas('sla', function ($query)use($request) {
                                    $query->whereHas('slaTemplate', function ($query)use($request) {
                                        $query->where('severity_id',$request->severity_id); 
                                }); 
                            });
                        })
                        ->when($request->code_sla, function ($query) use ($request) {
                            return $query->where('code_sla',$request->code_sla);
                        })
                        ->when($request->received_via, function ($query) use ($request) {
                            return $query->where('received_via',$request->received_via);
                        })
                        ->when($request->created_by, function ($query) use ($request) {
                            return $query->whereHas('createdBy', function ($query)use($request) {
                                    $query->where('name',$request->created_by); 
                            });
                        })
                        ->when($request->complaint_by, function ($query) use ($request) {
                            return $query->whereHas('complaintUser', function ($query)use($request) {
                                    $query->where('name',$request->complaint_by); 
                            });
                        })
                        ->when($request->close_by, function ($query) use ($request) {
                            return $query->whereHas('resolvedByUser', function ($query)use($request) {
                                    $query->where('name',$request->close_by); 
                            });
                        })    
                        ->when($request->state_id, function ($query)use ($request) {
                            $query->whereHas('branch', function ($query)use($request) {
                                $query->where('state_id',$request->state_id); 
                            });
                        })
                        ->when($request->start_date, function ($query) use ($request){
                            $query->whereDate('incident_date','>=',$request->start_date);
                        })
                        ->when($request->end_date, function ($query) use ($request){
                            $query->whereDate('incident_date','<=',$request->end_date);
                        })
                        ->when($request->start_close_date, function ($query) use ($request){
                            $query->whereDate('actual_end_date','>=',$request->start_close_date);
                        })
                        ->when($request->end_close_date, function ($query) use ($request){
                            $query->whereDate('actual_end_date','<=',$request->end_close_date);
                        })
                        ->when($request->asset_siri_no, function ($query) use ($request){
                            $query->where('asset_siri_no',$request->asset_siri_no);
                        })
                        ->when($request->incident_no, function ($query) use ($request){
                            $query->where('incident_no',$request->incident_no);
                        })
                        
                        ->paginate($limit);

        return $data;
    }
}
