<?php

namespace App\Http\Services;
use App\Models\ActionCode;
use App\Http\Resources\ActionCodeResources;
use App\Http\Traits\ResponseTrait;

class ActionCodeServices
{
    use ResponseTrait;
    
    public static function create($data){

        try{

            if(isset($data['role_id'])){
                $data['role_id'] = json_encode($data['role_id']);
            }

            $create = ActionCode::create($data);

            $return = new ActionCodeResources($create);

            return self::success('Success', $return);
        } 
        catch (\Throwable $th) {
            return self::error($th->getMessage());
        }
    }

    public static function update(ActionCode $action_code,$data){

        try{
            if(isset($data['role_id'])){
                $data['role_id'] = json_encode($data['role_id']);
            }
            
            $create = $action_code->update($data);

            $return = new ActionCodeResources($action_code);

            return self::success('Success', $return);
        } 
        catch (\Throwable $th) {
            return self::error($th->getMessage());
        }
    }

    public static function delete(ActionCode $action_code){
        $action_code->delete();

        return self::success('Success', true);
    }
}