<?php

namespace App\Models;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Model;

class Calendar extends BaseModel
{
    protected $fillable = [ 
        'name',
        'start_date',
        'end_date',
        'state_id',
        'description',
        'is_active',
    ];

    protected $casts = [
        'start_date' => 'datetime:Y-m-d',
        'end_date' => 'datetime:Y-m-d',
    ];

    protected array $defaultSort = [
        'start_date' => 'asc'
    ];

    protected array $filterable = ['name','start_date','end_date','state_id','is_active'];


    public function stateDescription(){
        return $this->hasOne(RefTable::class,'ref_code','state_id')->where('code_category', 'state');
    }

    public function getStateDesc($state_id){
        $state_ids = json_decode($state_id, true);  

        if (is_array($state_ids) && in_array(0, $state_ids)) {
            return 'Semua Negeri';
        }

        $data = RefTable::where('code_category', 'state')
                        ->whereIn('ref_code', $state_ids)
                        ->pluck('name', 'ref_code');  

        $ordered = collect($state_ids)->map(function($id) use ($data) {
            return $data[$id] ?? null;
        })->filter()->values(); 
        
        return $ordered;
    }

    public static function getPublicHoliday($state_id, $year){

        $public_holiday = Calendar::where(function($query) use ($state_id) {
                                    $query->whereRaw("JSON_EXISTS(state_id, '$?(@ == 0)')") 
                                    ->orWhereRaw("JSON_EXISTS(state_id, '$?(@ == $state_id)')"); 
                                })
                                ->whereYear('start_date', $year)
                                ->get();

        return $public_holiday;
    }
}
