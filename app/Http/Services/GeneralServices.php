<?php

namespace App\Http\Services;

use Illuminate\Support\Facades\Auth;
use App\Models\Role;
use App\Models\Category;
use App\Models\Group;
use App\Models\SlaTemplate;
use App\Models\Branch;
use App\Models\User;
use App\Models\Company;
use App\Models\Complaint;
use App\Models\Sla;
use App\Models\CompanyContract;
use App\Models\OperatingTime;
use App\Models\ActionCode;

class GeneralServices
{
    public static function dynamicOption($request){
        $data = [];

        foreach($request->code as $code){

            if($code == 'role'){
                $data[$code] = Role::select('id','name','name_en')
                                    ->orderBy('name','asc')
                                    ->get();
            }

            if($code == 'category'){
                $data[$code] = Category::select('id','name','level','code')
                                        ->when($request->branch_id, function ($query) use ($request) {
                                            return $query->whereHas('sla', function ($query)use($request) {
                                                $query->whereRaw("JSON_EXISTS(branch_id, '\$[*] ? (@ == $request->branch_id)')");
                                            });
                                        })
                                        ->with(['sla' => function ($query) {
                                            $query->select('id','code','category_id');
                                        }])
                                        ->where('is_active',true)
                                        ->orderBy('name','asc')
                                        ->get();
            }

            if($code == 'branch'){
                $data[$code] = Branch::select('id','name','category','state_id','location')
                                        ->when($request->category, function ($query) use ($request) {
                                            return $query->where('category',$request->category);
                                        })
                                        ->when($request->operating_time, function ($query) {
                                            return $query->doesntHave('operatingTime');
                                        })
                                        ->orderBy('category','desc')
                                        ->orderBy('name','asc')
                                        ->get()->groupBy(function($item) {
                                            return $item->state_id ? $item->stateDescription->name_en : 'Unknown State';
                                        })
                                        ->sortKeys();
            }

            if($code == 'sla_template'){
                $data[$code] = SlaTemplate::select('id','code','severity_id','service_level','company_id','company_contract_id','response_time','response_time_type','response_time_penalty','resolution_time','resolution_time_type','resolution_time_penalty','response_time_location','response_time_location_type','response_time_location_penalty',   
                                                    'temporary_resolution_time','temporary_resolution_time_type','temporary_resolution_time_penalty','dispatch_time','dispatch_time_type')
                                                    ->with(['severityDescription' => function ($query) {
                                                        $query->select('ref_code','name','name_en');
                                                    }])
                                                    ->with(['responseTimeTypeDescription' => function ($query) {
                                                        $query->select('ref_code','name','name_en');
                                                    }])
                                                    ->with(['resolutionTimeTypeDescription' => function ($query) {
                                                        $query->select('ref_code','name','name_en');
                                                    }])
                                                    ->with(['responseTimeLocationTypeDescription' => function ($query) {
                                                        $query->select('ref_code','name','name_en');
                                                    }])
                                                    ->with(['temporaryResolutionTimeTypeDescription' => function ($query) {
                                                        $query->select('ref_code','name','name_en');
                                                    }])
                                                    ->with(['dispatchTimeTypeDescription' => function ($query) {
                                                        $query->select('ref_code','name','name_en');
                                                    }])
                                                    ->with(['company' => function ($query) {
                                                        $query->select('id','name');
                                                    }])
                                                    ->with(['companyContract' => function ($query) {
                                                        $query->select('id','name','start_date','end_date');
                                                    }])
                                                    ->orderBy('code','asc')
                                                    ->get();

                                                    
            }

            if($code == 'group'){
                $contractor = Auth::user()->roles->contains('role', Role::CONTRACTOR);
               
                $data[$code] = Group::select('id','name','description')
                                    ->where('is_active',true)
                                    // ->whereHas('userGroup.userDetails')
                                    ->when($contractor && $request->own_group, function ($query) {
                                        return $query->where(function ($subQuery) {
                                            $subQuery->whereHas('userGroup', function ($q)  {
                                                $q->where('user_id', Auth::user()->id);
                                            })
                                            ->orWhereHas('userGroupAccess', function ($q)  {
                                                $q->where('user_id', Auth::user()->id);
                                            });
                                        ;})
                                    ;})
                                    ->orderBy('name','asc')
                                    ->get();
            }

            if($code == 'user'){
                $data[$code] = User::select('id','name','nickname','email','phone_no','address','fax_no','postcode','state_id')
                                    ->when($request->group_id, function ($query) use ($request) {
                                        return $query->whereHas('group', function ($query)use($request) {
                                            $query->where('groups_id',$request->group_id); 
                                        });
                                    })
                                    ->when($request->branch_id, function ($query)use($request) {
                                        return $query->where('branch_id',$request->branch_id);
                                    })
                                    ->where('is_active',true)
                                    ->orderBy('name','asc')
                                    ->get();
            }

            if($code == 'company'){
                $data[$code] = Company::select('id','name','nickname')
                                    ->where('is_active',true)
                                    ->orderBy('name','asc')
                                    ->get();
            }

            if($code == 'complaint'){
                $data[$code] = Complaint::select('id','name','email','phone_no','office_phone_no','address','postcode','state_id','extension_no')
                                        ->when($request->state_id, function ($query)use($request) {
                                            return $query->where('state_id',$request->state_id);
                                        })
                                        ->orderBy('name','asc')
                                        ->get();
                
                if($request->group_by){
                    $data[$code] = $data[$code]->groupBy(function($item) {
                                                    return $item->state_id ? $item->stateDescription->name_en : 'Unknown State';
                                                })
                                                ->sortKeys();
                }
            }

            if($code == 'category_sla'){
                $data[$code] = Sla::select('id','category_id','code','sla_template_id')
                                            // ->when($request->branch_id, function ($query) use ($request) {
                                            //     return $query->whereRaw("JSON_TEXTCONTAINS(branch_id, '$', '1')");
 
                                            // })
                                            ->with(['slaTemplate' => function ($query) {
                                                $query->select('id','sla_template.code','service_level','severity_id');
                                            }])
                                            ->orderBy('code','asc')
                                            ->get();
                                    

            }

            if($code == 'company_contract'){
                $data[$code] = CompanyContract::select('id','name','company_id')->where('is_active',true)
                                            ->when($request->company_id, function ($query) use ($request) {
                                                return $query->where('company_id',$request->company_id);
                                            })
                                            ->orderBy('name','asc')
                                            ->get();

            }

            if($code == 'action_code'){
                $contractor = Auth::user()->roles->contains('role', Role::CONTRACTOR);
                $frontliner = Auth::user()->roles->contains('role', Role::FRONTLINER);

                
                $data[$code] = ActionCode::select('name','nickname','description')
                                        ->when($contractor, function ($query) use ($request) {
                                            $list_action = [ActionCode::PROG,ActionCode::ACTR,ActionCode::RETURN,ActionCode::DISC,ActionCode::ONSITE];

                                            return $query->whereIn('nickname',$list_action);
                                        })
                                        ->when($frontliner, function ($query) use ($request) {
                                            $list_action = [ActionCode::UPDATE,ActionCode::ACTR,ActionCode::DISC,ActionCode::VERIFY,ActionCode::CLOSED];

                                            return $query->whereIn('nickname',$list_action);
                                        })
                                        ->where('nickname', '!=', ActionCode::INITIAL)
                                        ->where('is_active',true)
                                        ->get();
                
            }

             if($code == 'main_category'){
                $data[$code] = Category::select('id','name','level','code')
                                        ->when($request->branch_id, function ($query) use ($request) {
                                            return $query->whereHas('sla', function ($query)use($request) {
                                                $query->whereRaw("JSON_EXISTS(branch_id, '\$[*] ? (@ == $request->branch_id)')");
                                            });
                                        })
                                        ->with(['sla' => function ($query) {
                                            $query->select('id','code','category_id');
                                        }])
                                        ->with(['childCategoryRecursive' => function ($query) {
                                            $query->select('id','category_id','name','level','code')->where('is_active',true);
                                        }])
                                        ->whereNull('category_id')
                                        ->where('is_active',true)
                                        ->orderBy('name','asc')
                                        ->get();
            }
        }
        return $data;
    }

} 