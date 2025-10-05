<?php

namespace App\Http\Services;
use App\Models\OperatingTime;
use App\Http\Resources\OperatingTimeResources;
use App\Http\Traits\ResponseTrait;

class OperatingTimeServices
{
    use ResponseTrait;

    public static function create($data){
        try{
            $create = null;

            foreach($data['branch_id'] as $branch_id){

                $data['branch_id'] = $branch_id;

                $create = OperatingTime::create($data);
            }

            $return = new OperatingTimeResources($create);

            return self::success('Success', $return);
        }
        catch (\Throwable $th) {
            return self::error($th->getMessage());
        }
    }

    public static function update(OperatingTime $operating_time,$data){
        try{

            $create = $operating_time->update($data);

            $return = new OperatingTimeResources($operating_time);

            return self::success('Success', $return);
        }
        catch (\Throwable $th) {
            return self::error($th->getMessage());
        }
    }

}