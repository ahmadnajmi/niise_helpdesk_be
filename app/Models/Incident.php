<?php

namespace App\Models;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

use DB;

class Incident extends BaseModel
{
    use HasFactory;
    protected $table = 'incidents';
    
    public $incrementing = false;
    protected $keyType = 'string';
    public $usesUuid = true;
    
    protected $fillable = [ 
        'incident_no',
        'code_sla',
        'incident_date',
        'barcode',
        'branch_id',
        'category_id',
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
        'operation_user_id',
        'expected_end_date',
        'actual_end_date',
        'status',
        'asset_parent_id',
        'asset_component_id',
        'sla_version_id',
        'service_recipient_id',
        'resolved_user_id',
        'assign_group_id',
        'assign_company_id'
    ];

    protected $casts = [
        'incident_date' => 'datetime:Y-m-d',
        'date_asset_loss' => 'datetime:Y-m-d',
        'date_report_police' => 'datetime:Y-m-d',
        'expected_end_date' => 'datetime:Y-m-d',
        'actual_end_date' => 'datetime:Y-m-d',
        // 'asset_component_id' => 'array',

    ];

    protected static $sortable = [
        'incident_no' => 'incident_no',
        'start_date' => 'incident_date',
        'end_date' => 'actual_end_date',
        'information' => 'information',
        'branch' => 'branch.name', 
        'severity' => 'sla.slaTemplate.severityDescription.name',
        'phone_no' => 'complaint.phone_no',
        'status' => 'status.statusDesc.name',
    ];

    const OPEN = 1;
    const RESOLVED = 2;
    const CLOSED = 3;
    const CANCEL_DUPLICATE = 4;
    const ON_HOLD = 5;
    const TEMPORARY_FIX = 6;
    
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

    // public static function generateIncidentNo(){
    //     $get_incident = self::orderBy('incident_no','desc')->first();

    //     if($get_incident){
    //         $code = $get_incident->incident_no;
    //         $old_code = substr($code, -5);
    //         $incremented = (int)$old_code + 1;
    //         $next_number = str_pad($incremented, 5, '0', STR_PAD_LEFT);
    //     } else {
    //         $next_number = '00001';
    //     }

    //     return 'TN'.date('Ymd').$next_number;
    // }
    public static function generateIncidentNo(){
        $today = date('Ymd');
        $prefix = 'TN' . $today;
        
        $get_incident = self::where('incident_no', 'LIKE', $prefix . '%')
                            ->orderBy('incident_no', 'desc')
                            ->first();
        
        if($get_incident){
            $code = $get_incident->incident_no;
            $old_code = substr($code, -5);
            $incremented = (int)$old_code + 1;
            $next_number = str_pad($incremented, 5, '0', STR_PAD_LEFT);
        } else {
            $next_number = '00001';
        }
        
        return $prefix . $next_number;
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

    public function incidentResolutionLatest(){
        return $this->hasOne(IncidentResolution::class, 'incident_id','id')->orderBy('created_at', 'desc'); 
    }

    public function incidentResolutionActr(){
        return $this->hasOne(IncidentResolution::class, 'incident_id','id')->where('action_codes',ActionCode::ACTR)->orderBy('created_at','asc');
    }


    public function incidentDocumentAppendix(){
        return $this->hasMany(IncidentDocument::class, 'incident_id','id')->where('type',IncidentDocument::APPENDIX)->orderBy('created_at','desc');
    }

    public function incidentDocumentAsset(){
        return $this->hasMany(IncidentDocument::class, 'incident_id','id')->where('type',IncidentDocument::ASSET)->orderBy('created_at','desc');
    }

    public function categoryDescription(){
        return $this->hasOne(Category::class,'id','category_id');
    }

    public function assignGroup(){
        return $this->hasOne(Group::class, 'id','assign_group_id');
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

    public function incidentPenalty(){
        return $this->hasOne(IncidentPenalty::class,'incident_id','id');
    }

    public function scopeSearch($query, $keyword){
        if (!empty($keyword)) {
            $keyword = strtolower($keyword);
            $lang = substr(request()->header('Accept-Language'), 0, 2); 

            $query->where(function($q) use ($keyword,$lang) {
                $q->whereRaw('LOWER(incident_no) LIKE ?', ["%{$keyword}%"]);
                $q->orWhereRaw('LOWER(information) LIKE ?', ["%{$keyword}%"]);
                
                $q->orWhereHas('sla.slaTemplate.severityDescription', function ($desc) use ($keyword, $lang) {
                    if ($lang === 'ms') {
                        $desc->whereRaw('LOWER(name) LIKE ?', ["%{$keyword}%"]);
                    } else{
                        $desc->whereRaw('LOWER(name_en) LIKE ?', ["%{$keyword}%"]);
                    }
                });
                                
                $q->orWhereHas('branch', function ($search) use ($keyword) {
                    $search->whereRaw('LOWER(name) LIKE ?', ["%{$keyword}%"]);
                });

                $q->orWhereHas('complaintUser', function ($search) use ($keyword) {
                    $search->whereRaw('LOWER(phone_no) LIKE ?', ["%{$keyword}%"]);
                });

                if ($this->isValidDate($keyword)) {
                    $q->orWhereDate('incident_date', $keyword);
                    $q->orWhereDate('actual_end_date', $keyword);
                }
            });
        }
        return $query;
    }

    public function scopeSortByField($query,$request){
     
        foreach ($request->all() as $key => $direction) {

            if (Str::endsWith($key, '_sort')) {

                $field = str_replace('_sort', '', $key);
                $direction = strtolower($direction);
                $sortable = static::$sortable[$field] ?? null;

                if (!in_array($direction, ['asc', 'desc']) || !$sortable) {
                    continue;
                }
                
                if (str_contains($sortable, '.')) {
                    [$relation, $column] = explode('.', $sortable);
                    
                    if($field === 'branch') {
                        $query->leftJoin('branch', 'branch.id', '=', 'incidents.branch_id')
                            ->select('incidents.*')
                            ->orderBy("branch.$column", $direction);
                    }
                    elseif($field === 'severity') {
                        $lang = substr(request()->header('Accept-Language'), 0, 2); 

                        $query->leftJoin('sla', 'sla.code', '=', 'incidents.code_sla')
                            ->leftJoin('sla_template', 'sla_template.id', '=', 'sla.sla_template_id')
                            ->leftJoin('ref_table', function ($join) {
                                $join->on('ref_table.ref_code', '=', 'sla_template.severity_id')
                                    ->where('ref_table.code_category', '=', 'severity');
                            })
                            ->orderByRaw("
                                LOWER(CASE 
                                    WHEN ? = 'ms' THEN ref_table.name 
                                    ELSE ref_table.name_en 
                                END) {$direction}
                            ", [$lang]);
                    }
                    elseif($field === 'phone_no') {

                        $query->leftJoin('users', 'users.id', '=', 'incidents.complaint_user_id')
                                ->select('incidents.*')
                                ->orderBy("users.$column", $direction);
                    }
                    elseif($field === 'status') {
                        $query->leftJoin('ref_table', function ($join) {
                            $join->on('ref_table.ref_code', '=', 'incidents.status')
                                ->where('ref_table.code_category', '=', 'incident_status');
                        })
                        ->orderByRaw("LOWER(ref_table.name) {$direction}");
                    }
                } 
                elseif($field === 'information') {
                    $query->orderByRaw("TO_CHAR(SUBSTR(information, 1, 4000)) {$direction}");
                }
               
                else {
                    $query->orderBy($sortable, $direction);
                }
            }
        }

        return $query;
    }

    private function isValidDate($date){
        if (empty($date)) {
            return false;
        }
        
        try {
            $parsedDate = \Carbon\Carbon::parse($date);
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    protected function calculateCountDownSettlement(): Attribute{
        return Attribute::get(function () {

            if(!$this->actual_end_date && $this->expected_end_date){
                $now  = now(); 
                $diff = $now->lessThan($this->expected_end_date)? $now->diff($this->expected_end_date): null;

                return $diff ? $diff->d .' Hari : ' . $diff->h . ' Jam : ' .$diff->i  .' Minit' : '00 Hari : 00 Jam : 00 Minit';
            }
            else{
                return '00 Hari : 00 Jam : 00 Minit';
            }
        });
    }

    protected function calculateBreachTime(): Attribute{
        return Attribute::get(function () {
            $date_actr = $this->incidentResolutionActr?->created_at;

            if(!$this->expected_end_date){
                return '00 Hari : 00 Jam : 00 Minit';
            }

            if (!$date_actr || $date_actr->lessThanOrEqualTo($this->expected_end_date)) {
                return '00 Hari : 00 Jam : 00 Minit';
            }
            else{
                $diff = $this->expected_end_date->diff($date_actr);

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

        $data =  Incident::select('id','incident_no','branch_id','information','status','incident_date','actual_end_date','code_sla','complaint_user_id','created_at','updated_at','created_by','updated_by')
                        ->applyFilters($request)
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
                                        ->where('expected_end_date', '>', now()->startOfDay()->modify('-4 days'));
                        })
                        ->when($request->type == 'tbb', function ($query) use ($request) {
                            return $query->where('status',Incident::OPEN)
                                        ->whereBetween('expected_end_date', [
                                            now()->startOfDay(),      
                                            now()->addDays(2)->endOfDay()   
                                        ]);
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
                        ->when($request->complaint_phone_no, function ($query) use ($request) {
                            return $query->whereHas('complaintUser', function ($query)use($request) {
                                    $query->where('phone_no',$request->complaint_phone_no); 
                            });
                        })
                         ->when($request->complaint_ic_no, function ($query) use ($request) {
                            return $query->whereHas('complaintUser', function ($query)use($request) {
                                    $query->where('ic_no',$request->complaint_ic_no); 
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
                            // $query->whereRaw('LOWER(incident_no) LIKE ?', ["%{$request->incident_no}%"]);
                            $query->where('incident_no', 'LIKE', "%{$request->incident_no}%");

                        })
                        ->when($request->group_id, function ($query)use($request){
                            $query->where('assign_group_id',$request->group_id); 
                        })
                        ->when($request->status_workbasket, function ($query) use ($request) {
                            return $query->whereHas('workbasket', function ($query)use($request) {
                                    $query->where('status',$request->status_workbasket); 
                            });
                        })
                        ->search($request->search)
                        ->sortByField($request)
                        ->paginate($limit);

        return $data;
    }


    public function scopeApplyFilters($query, $request){
        $role = User::getUserRole(Auth::user()->id);
        
        $query->when($role?->role == Role::JIM, function ($query){
                $query->where('complaint_user_id',Auth::user()->id);
            })
            ->when($role?->role == Role::CONTRACTOR, function ($query){
                $group_id = UserGroup::where('ic_no',Auth::user()->ic_no)->pluck('groups_id');

                return $query->whereIn('incidents.assign_group_id',$group_id);
            })
            ->when($role?->role == Role::BTMR_SECOND_LEVEL, function ($query){
                $category_id = json_decode(Auth::user()->category_id);

                return $query->whereIn('incidents.category_id',$category_id);
            })
            ->when($request->company_id, function ($query) use ($request) {
                return $query->where('incidents.assign_company_id',$request->company_id);
            })
            ->when($request->branch_id, function ($query) use ($request) {
                return $query->where('incidents.branch_id',$request->branch_id);
            });

        return $query;
    }
}
