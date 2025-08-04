<?php

namespace App\Http\Services;
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

class GeneralServices
{
    public static function dynamicOption($request){
        $data = [];

        foreach($request->code as $code){

            if($code == 'role'){
                $data[$code] = Role::select('id','name','name_en')->get();
            }

            if($code == 'category'){
                $data[$code] = Category::select('id','name','level','code')->where('is_active',true)->get();
            }

            if($code == 'branch'){
                $data[$code] = Branch::select('id','name','category','state_id')->get();
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
                                                        $query->select('id','name');
                                                    }])
                                                    ->get();

                                                    
            }

            if($code == 'group'){
                $data[$code] = Group::select('id','name','description')->where('is_active',true)->get();
            }

            if($code == 'user'){
                $data[$code] = User::select('id','name','nickname')
                                    ->when($request->group_id, function ($query) use ($request) {
                                        return $query->whereHas('group', function ($query)use($request) {
                                            $query->where('groups_id',$request->group_id); 
                                        });
                                    })
                                    ->where('is_active',true)
                                    ->orderBy('name','asc')
                                    ->get();
            }

            if($code == 'company'){
                $data[$code] = Company::select('id','name','nickname')->where('is_active',true)->get();
            }

            if($code == 'complaint'){
                $data[$code] = Complaint::select('id','name','email','phone_no','office_phone_no','extension_no')->get();
            }

            if($code == 'category_sla'){
                $data[$code] = Sla::select('id','category_id','code','sla_template_id')
                                            // ->when($request->branch_id, function ($query) use ($request) {
                                            //     return $query->whereRaw("JSON_TEXTCONTAINS(branch_id, '$', '1')");
 
                                            // })
                                            ->with(['slaTemplate' => function ($query) {
                                                $query->select('id','sla_template.code','service_level','severity_id');
                                            }])
                                            
                                            ->get();
                                    

            }

            if($code == 'company_contract'){
                $data[$code] = CompanyContract::select('id','name','company_id')->where('is_active',true)
                                            ->when($request->company_id, function ($query) use ($request) {
                                                return $query->where('company_id',$request->company_id);
                                            })
                                            ->get();

            }
        }
        return $data;
    }

} 