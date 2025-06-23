<?php

namespace App\Http\Services;
use App\Models\Role;
use App\Models\Category;
use App\Models\Group;
use App\Models\SlaTemplate;
use App\Models\Branch;
use App\Models\User;
use App\Models\Company;

class GeneralServices
{
    public static function dynamicOption($request){
        $data = [];

        foreach($request->code as $code){

            if($code == 'role'){
                $data['role'] = Role::select('id','name','name_en')->where('name','!=','Pentadbir Helpdesk Sistem (BTMR)')->get();
            }

            if($code == 'category'){
                $data['category'] = Category::select('id','name','level','code')->where('is_active',true)->get();
            }

            if($code == 'branch'){
                $data['branch'] = Branch::select('id','name','category','state')->get();
            }

            if($code == 'sla_template'){
                $data['sla_template'] = SlaTemplate::select('id','code','severity_id','service_level')
                                                    ->with(['severityDescription' => function ($query) {
                                                        $query->select('ref_code','name');
                                                    }])
                                                    ->get();
            }

            if($code == 'group'){
                $data['group'] = Group::select('id','name','description')->where('is_active',true)->get();
            }

            if($code == 'user'){
                $data['user'] = User::select('id','name','nickname')->where('is_active',true)->get();
            }

            if($code == 'company'){
                $data['company'] = Company::select('id','name','nickname')->where('is_active',true)->get();
            }
        }
        return $data;
    }

}