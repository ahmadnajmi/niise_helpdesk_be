<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Holiday\MalaysiaHoliday;
use App\Models\Calendar;
use App\Models\RefTable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class holidays extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'holidays {year}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get holidays malaysia based on specific year';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $year = $this->argument('year');

        Log::info('Scheduler ran at ' . now());

        Calendar::truncate();

        if (DB::getDriverName() === 'oracle') {
            DB::statement("ALTER SEQUENCE CALENDARS_ID_SEQ RESTART START WITH 1");
        } 
        try {
            $holiday = new MalaysiaHoliday;
            $result = $holiday->fromState(MalaysiaHoliday::$region_array,$year)->get();

            if($result['status']){
                foreach($result['data'] as $states){

                    if($states['regional'] == 'Kuala Lumpur') {
                        $states['regional'] = 'Wilayah Persekutuan Kuala Lumpur';
                    }
                    elseif($states['regional'] == 'Labuan') {
                        $states['regional'] = 'Wilayah Persekutuan Labuan';
                    }
                    elseif($states['regional'] == 'Putrajaya') {
                        $states['regional'] = 'Wilayah Persekutuan Putrajaya';
                    }
                    $get_state = RefTable::where('code_category','state')->where('name_en',$states['regional'])->first();

                    // if(!$get_state) dd($get_state,$states['regional']);


                    foreach($states['collection'] as $year){

                        foreach($year['data'] as $year){
        
                            $get_calendar = Calendar::where('name',$year['name'])->where('start_date',$year['date'])->first();

                            if($get_calendar){
                                $old_state = json_decode($get_calendar->state_id, true) ?? [];

                                $old_state[] = (int)$get_state->ref_code;

                                $old_state = array_unique($old_state);

                                $get_calendar->update(['state_id' => json_encode($old_state)]);

                            }
                            else{
                                $data_calendar['name'] = $year['name'];
                                $data_calendar['start_date'] = $year['date'];
                                $data_calendar['end_date'] = $year['date'];
                                $data_calendar['description'] = $year['description'];
                                $data_calendar['state_id'] = json_encode([(int) $get_state->ref_code]);
                                

                                $get_calendar = Calendar::where('name',$data_calendar['name'])
                                                        ->where('start_date',$data_calendar['start_date'])
                                                        ->where('end_date',$data_calendar['end_date'])
                                                        ->where('state_id',$data_calendar['state_id'])
                                                        ->first();
                                
                                if(!$get_calendar){
                                    $create = Calendar::create($data_calendar);
                                }
                            }

                            
                        }

                    }
                }
            }

            $calendars = Calendar::whereNotNull('state_id')->get()->filter(function ($calendar) {
                $state_ids = json_decode($calendar->state_id, true);
                return is_array($state_ids) && count($state_ids) === 16;
            });

            foreach($calendars as $calendar){
                $calendar->update(['state_id' => json_encode([0]) ]);
            }

           

        } 
        catch (\Throwable $e) {
            Log::critical("Unexpected scheduler failure: " . $e->getMessage());
            return Command::FAILURE;
        }

        Log::info('Scheduler ran done at ' . now());
       
    }
}
