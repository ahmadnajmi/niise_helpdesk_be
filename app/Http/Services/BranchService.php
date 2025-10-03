<?php

namespace App\Http\Services;
use App\Models\Branch;
use App\Http\Resources\BranchResources;
use App\Http\Traits\ResponseTrait;

class BranchService
{
    use ResponseTrait;

    public static function create($data){

        try{
           
            $get_branch = Branch::find($data['branch_code']);

            $data['id'] = $data['branch_code'];

            if($get_branch){
                $update = $get_branch->update($data);
            }
            else{
                $get_branch = Branch::create($data);
            }

            $data =  new BranchResources($get_branch);

            return self::success('Success', $data);

        } 
        catch (\Throwable $th) {
            return self::error($th->getMessage());
        }
    }
}