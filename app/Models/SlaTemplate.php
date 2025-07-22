<?php

namespace App\Models;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Model;

class SlaTemplate extends BaseModel
{
    protected $table = 'sla_template';

    protected $fillable = [
        'code',
        'severity_id',
        'service_level',
        'timeframe_channeling',
        'timeframe_channeling_type',
        'timeframe_incident',
        'timeframe_incident_type',

        'response_time_reply',
        'response_time_reply_type',
        'response_time_reply_penalty',

        'timeframe_solution',
        'timeframe_solution_type',
        'timeframe_solution_penalty',

        'response_time_location',
        'response_time_location_type',
        'response_time_location_penalty',

        'notes'
    ];

     public function scopeSearch($query, $keyword){
        if (!empty($keyword)) {
            $lang = substr(request()->header('Accept-Language'), 0, 2);

            $query->where(function($q) use ($keyword,$lang) {
                $q->where('code', 'like', "%$keyword%");
              
                $q->orWhere('timeframe_channeling','like', "%$keyword%");

                $q->orWhere('service_level','like', "%$keyword%");

               $q->orWhereHas('severityDescription', function ($search) use ($keyword, $lang) {
                    $search->when($lang === 'ms', function ($ref_table) use ($keyword) {
                        $ref_table->where('name', 'like', "%$keyword%");
                    });
                    $search->when($lang === 'en', function ($ref_table) use ($keyword) {
                        $ref_table->where('name_en', 'like', "%$keyword%");
                    });
                });
            });
        }
        return $query;
    }

    public function scopeSortByField($query, $fields){
        if(isset($fields)){
            foreach($fields as $column => $order_by){
                $query->orderBy($column,$order_by);
            }
        }
        return $query;
    }

    public function severityDescription(){
        return $this->hasOne(RefTable::class,'ref_code','severity_id')->where('code_category', 'severity');
    }

    public function channelingTypeDescription(){
        return $this->hasOne(RefTable::class,'ref_code','timeframe_channeling_type')->where('code_category', 'sla_type');
    }

    public function incidentTypeDescription(){
        return $this->hasOne(RefTable::class,'ref_code','timeframe_incident_type')->where('code_category', 'sla_type');
    }

    public function replyTypeDescription(){
        return $this->hasOne(RefTable::class,'ref_code','response_time_reply_type')->where('code_category', 'sla_type');
    }

    public function solutionTypeDescription(){
        return $this->hasOne(RefTable::class,'ref_code','timeframe_solution_type')->where('code_category', 'sla_type');
    }

    public function locationTypeDescription(){
        return $this->hasOne(RefTable::class,'ref_code','response_time_location_type')->where('code_category', 'sla_type');
    }
    
    public function replyPenaltyDescription(){
        return $this->hasOne(RefTable::class,'ref_code','response_time_reply_penalty')->where('code_category', 'penalty_response_time');
    }

    public function solutionPenaltyDescription(){
        return $this->hasOne(RefTable::class,'ref_code','timeframe_solution_penalty')->where('code_category', 'penalty_timeframe_solution');
    }

    public function locationPenaltyDescription(){
        return $this->hasOne(RefTable::class,'ref_code','response_time_location_penalty')->where('code_category', 'penalty_response_time_location');
    }
}