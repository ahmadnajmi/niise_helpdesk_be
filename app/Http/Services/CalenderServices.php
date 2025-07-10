<?php

namespace App\Http\Services;
use App\Models\Calendar;
use App\Http\Resources\CalendarResources;

class CalenderServices
{
    public static function create($data){

        $create = self::processData($data);

        $return = new CalendarResources($create);

        return $return;
    }

    public static function update(Calendar $calendar,$data){
     
        $create = $calendar->update($data);

        $return = new CalendarResources($calendar);

        return $return;
    }

    public static function delete(Calendar $calendar){

        $calendar->delete();

        return true;
    }


    public static function processData($data){
        $create = null;

        foreach($data['state_id'] as $state){
            $new_data = $data;

            $new_data['state_id'] = $state;

            $create = Calendar::create($new_data);
        }
        return $create;
    }
}