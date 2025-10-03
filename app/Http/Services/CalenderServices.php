<?php

namespace App\Http\Services;
use App\Models\Calendar;
use App\Http\Resources\CalendarResources;
use App\Http\Traits\ResponseTrait;

class CalenderServices
{
    use ResponseTrait;

    public static function create($data){
        try{
            $data['state_id'] = json_encode($data['state_id']);

            $create = Calendar::create($data);

            $return = new CalendarResources($create);

            return self::success('Success', $return);
        } 
        catch (\Throwable $th) {
            return self::error($th->getMessage());
        }
    }

    public static function update(Calendar $calendar,$data){
        try{
            $data['state_id'] = json_encode($data['state_id']);

            $create = $calendar->update($data);

            $return = new CalendarResources($calendar);

            return self::success('Success', $return);
        }
        catch (\Throwable $th) {
            return self::error($th->getMessage());
        }
    }

    public static function delete(Calendar $calendar){

        $calendar->delete();

        return self::success('Success', true);
    }
}