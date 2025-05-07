<?php

namespace App\Http\Services;
use App\Models\Role;
use App\Models\Category;

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
        }
        return $data;
    }

}