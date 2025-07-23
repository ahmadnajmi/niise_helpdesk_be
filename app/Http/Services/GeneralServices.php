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

class GeneralServices
{
    public static function dynamicOption($request){
        $data = [];

        foreach($request->code as $code){

            if($code == 'role'){
                $data['role'] = Role::select('id','name','name_en')->get();
            }

            if($code == 'category'){
                $data['category'] = Category::select('id','name','level','code')->where('is_active',true)->get();
            }

            if($code == 'branch'){
                $data['branch'] = Branch::select('id','name','category','state_id')->get();
            }

            if($code == 'sla_template'){
                $data['sla_template'] = SlaTemplate::select('id','code','severity_id','service_level','timeframe_channeling','timeframe_channeling_type','timeframe_incident','timeframe_incident_type','response_time_reply','response_time_reply_type','timeframe_solution','timeframe_solution_type','response_time_location','response_time_location_type')
                                                    ->with(['severityDescription' => function ($query) {
                                                        $query->select('ref_code','name','name_en');
                                                    }])
                                                    ->with(['channelingTypeDescription' => function ($query) {
                                                        $query->select('ref_code','name','name_en');
                                                    }])
                                                    ->with(['incidentTypeDescription' => function ($query) {
                                                        $query->select('ref_code','name','name_en');
                                                    }])
                                                    ->with(['replyTypeDescription' => function ($query) {
                                                        $query->select('ref_code','name','name_en');
                                                    }])
                                                    ->with(['locationTypeDescription' => function ($query) {
                                                        $query->select('ref_code','name','name_en');
                                                    }])
                                                    ->with(['solutionTypeDescription' => function ($query) {
                                                        $query->select('ref_code','name','name_en');
                                                    }])
                                                    ->get();
            }

            if($code == 'group'){
                $data['group'] = Group::select('id','name','description')->where('is_active',true)->get();
            }

            if($code == 'user'){
                $data['user'] = User::select('id','name','nickname')
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
                $data['company'] = Company::select('id','name','nickname')->where('is_active',true)->get();
            }

            if($code == 'complaint'){
                $data['complaint'] = Complaint::select('id','name','email','phone_no','office_phone_no','extension_no')->get();
            }
        }
        return $data;
    }

} 