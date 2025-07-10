<?php

namespace App\Http\Controllers;

use App\Models\Calendar;
use Illuminate\Http\Request;
use App\Http\Traits\ResponseTrait;
use App\Http\Collection\CalendarCollection;
use App\Http\Resources\CalendarResources;
use App\Http\Requests\CalendarRequest;
use App\Http\Services\CalenderServices;

class CalendarController extends Controller
{
    use ResponseTrait;

    public function index(Request $request)
    {
        $limit = $request->limit ? $request->limit : 15;
        
        $data =  Calendar::paginate($limit);

        return new CalendarCollection($data);
    }

    public function store(CalendarRequest $request)
    {
        // try {
            $data = $request->all();

            $data = CalenderServices::create($data);
           
            return $this->success('Success', $data);
          
        // } catch (\Throwable $th) {
        //     return $this->error($th->getMessage());
        // }
    }

    public function show(Calendar $calendar)
    {
        $data = new CalendarResources($calendar);

        return $this->success('Success', $data);
    }

    public function update(CalendarRequest $request, Calendar $calendar)
    {
        try {
            $data = $request->all();

            $data = CalenderServices::update($calendar,$data);

            return $this->success('Success', $data);
          
        } catch (\Throwable $th) {
            return $this->error($th->getMessage());
        }
    }

    public function destroy(Calendar $calendar)
    {
        $data = CalenderServices::delete($calendar);

        return $this->success('Success', null);
    }
}
