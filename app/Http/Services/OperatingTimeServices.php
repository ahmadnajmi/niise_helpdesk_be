<?php

namespace App\Http\Services;
use App\Models\OperatingTime;
use App\Http\Resources\OperatingTimeResources;

class OperatingTimeServices
{
    public static function create($data){

        $create = null;

        foreach($data['branch_id'] as $branch_id){

            $data['branch_id'] = $branch_id;

            $create = OperatingTime::create($data);
        }

        $return = new OperatingTimeResources($create);

        return $return;
    }

    public static function update(OperatingTime $operating_time,$data){

        $create = $operating_time->update($data);

        $return = new OperatingTimeResources($operating_time);

        return $return;
    }

}