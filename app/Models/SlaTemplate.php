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

        'response_time',
        'response_time_type',
        'response_time_penalty',

        'resolution_time',
        'resolution_time_type',
        'resolution_time_penalty',

        'response_time_location',
        'response_time_location_type',
        'response_time_location_penalty',

        'temporary_resolution_time',
        'temporary_resolution_time_type',
        'temporary_resolution_time_penalty',

        'dispatch_time',
        'dispatch_time_type',

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


    public function responseTimeTypeDescription(){
        return $this->hasOne(RefTable::class,'ref_code','response_time_type')->where('code_category', 'sla_type');
    }

    public function resolutionTimeTypeDescription(){
        return $this->hasOne(RefTable::class,'ref_code','resolution_time_type')->where('code_category', 'sla_type');
    }

    public function responseTimeLocationTypeDescription(){
        return $this->hasOne(RefTable::class,'ref_code','response_time_location_type')->where('code_category', 'sla_type');
    }

    public function temporaryResolutionTimeTypeDescription(){
        return $this->hasOne(RefTable::class,'ref_code','temporary_resolution_time_type')->where('code_category', 'sla_type');
    }

    public function dispatchTimeTypeDescription(){
        return $this->hasOne(RefTable::class,'ref_code','dispatch_time_type')->where('code_category', 'sla_type');
    }
    
}