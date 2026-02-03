<?php

namespace App\Http\Services;
use App\Models\Sla;
use App\Models\Category;
use App\Models\Branch;
use App\Http\Resources\SlaResources;
use App\Http\Traits\ResponseTrait;

class SlaServices
{
    use ResponseTrait;

    public static function create($data){
        try{
            $sla_id = $messages = $validBranches =  [];

            $branchIds = $data['branch_id'];

            if (is_string($branchIds)) {
                $branchIds = json_decode($branchIds, true);
            }

            foreach($data['sla_category'] as $sla_category){
                $validBranches = [];

                foreach($branchIds as $branch_id){
                    $sla_exists = Sla::where('category_id',$sla_category)
                                ->where(function ($q) use ($branch_id) {
                                    $q->orWhereRaw(
                                        "JSON_EXISTS(branch_id, '$?(@ == $branch_id)')"
                                    );
                                })
                                ->exists();

                    if($sla_exists) {
                        $branchName = Branch::where('id', $branch_id)->value('name');
                        $categoryName = Category::where('id', $sla_category)->value('name');

                        $messages[] = "{$branchName} already exists for category {$categoryName}";
                    }
                    else{
                        $validBranches[] = $branch_id;
                    }
                }

                if(count($validBranches) > 0){
                    $data['category_id'] = $sla_category;
                    $data['code'] = self::generateCode($sla_category);
                    $data['branch_id'] = json_encode($validBranches);

                    $create = Sla::create($data);
                    
                    $sla_id[] = $create->id;
                } 
            }

            if(count($sla_id) > 0){
                $data = new SlaResources($create);
                $message = $messages;
                $code = 200;
            } 
            else{
                $message = $messages;
                $data = null;
                $code = 500;
            }   

            $return  = [
                'status_code' => $code,
                'message' => $message,
                'data' => $data,
            ]; 
            
            return self::generalResponse($return);
        }
        catch (\Throwable $th) {
            return self::error($th->getMessage());
        }

        return $return;
    }

    public static function update(Sla $sla,$data){

        try{
            $data['branch_id'] = json_encode($data['branch_id']);

            $update = $sla->update($data);

            $return = new SlaResources($sla);

            $return  = [
                'message' => 'Success',
                'data' => $data,
                'status_code' => 200,
            ]; 

            return self::generalResponse($return);
        }
        catch (\Throwable $th) {
            return self::error($th->getMessage());
        }

        return $return;
    }

    public static function delete(Sla $sla){
        $sla->delete();

        return true;
    }

    public static function generateCode($category_id){
        $get_sla = Sla::where('category_id',$category_id)->orderBy('code','desc')->first();

        $category = Category::find($category_id);

        if($get_sla){
            $code = $get_sla->code;

            $old_code = substr($code, -2);

            $next_number = str_pad($old_code + 1, 2, '0', STR_PAD_LEFT); // "06"
        }
        else{
            $next_number = '01';
        }

        $new_code = strtoupper($category->name).$next_number;
        
        return $new_code;
    }
}