<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServiceLevelTemplate extends Model
{
      public $timestamps = false;

    public $incrementing = false;
    
    protected $table = 'HD_SLA_Template';
    
    protected $primaryKey = 'st_code';

    public function getMaxid(){
        return $this->select('st_code')->max('st_code');
    }

    public function getSlaTemplateIndex(){
        return $this->select('st_code','st_severity_lvl','st_escalation_time','st_due_date_timeframe','st_service_lvl')
                    ->orderBy('st_code','ASC');
    }

    public function getTimeType($timeInMinutes) {
        if (is_int($timeInMinutes / 60 )) {
            if (is_int($timeInMinutes / 60 / 24)) {
                return ($timeInMinutes / 60 / 24).'^d';
            } else {
                return ($timeInMinutes / 60).'^h';
            }
        } else {
            return $timeInMinutes.'^m';
        }
    }

    public function getTemplate($id){
        return $this->where('st_code',$id)
                    ->get();
    }
}
