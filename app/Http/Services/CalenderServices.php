<?php

namespace App\Http\Services;
use App\Models\Calendar;
use App\Http\Resources\CalendarResources;

class CalenderServices
{
    public static function create($data){
        
        $data['state_id'] = json_encode($data['state_id']);

        $create = Calendar::create($data);

        $return = new CalendarResources($create);

        return $return;
    }

    public static function update(Calendar $calendar,$data){

        $data['state_id'] = json_encode($data['state_id']);

        $create = $calendar->update($data);

        $return = new CalendarResources($calendar);

        return $return;
    }

    public static function delete(Calendar $calendar){

        $calendar->delete();

        return true;
    }
}