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
}
