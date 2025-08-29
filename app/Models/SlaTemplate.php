<?php

namespace App\Models;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SlaTemplate extends BaseModel
{
    use HasFactory;
    protected $table = 'sla_template';

    protected $fillable = [
        'code',
        'severity_id',
        'service_level',
        'company_id',
        'company_contract_id',

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

    const SLA_TYPE_MINUTE = 1;
    const SLA_TYPE_HOUR = 2;
    const SLA_TYPE_DAY = 3;

    protected static function booted(){
        static::creating(function ($model) {
            $last = SlaTemplate::orderBy('code', 'desc')->first();
            
            if ($last) {
                $old_code = (int) substr($last->code, -4); // get last 4 digits
                $next_number = str_pad($old_code + 1, 4, '0', STR_PAD_LEFT);
            } else {
                $next_number = '0001';
            }

            $model->code = 'ST' . $next_number;
        });
    }


    public function scopeSearch($query, $keyword){
        if (!empty($keyword)) {
            $lang = substr(request()->header('Accept-Language'), 0, 2);

            $keyword = strtolower($keyword);

            $query->where(function($q) use ($keyword,$lang) {
                $q->whereRaw('LOWER(code) LIKE ?', ["%{$keyword}%"]);

                $q->orWhereHas('severityDescription', function ($search) use ($keyword, $lang) {
                    $search->when($lang === 'ms', function ($ref_table) use ($keyword) {
                        $ref_table->whereRaw('LOWER(name) LIKE ?', ["%{$keyword}%"]);
                    });
                    $search->when($lang === 'en', function ($ref_table) use ($keyword) {
                        $ref_table->whereRaw('LOWER(name_en) LIKE ?', ["%{$keyword}%"]);
                    });
                });

                $q->orWhereHas('company', function ($search) use ($keyword) {
                    $search->whereRaw('LOWER(name) LIKE ?', ["%{$keyword}%"]);
                });

                $q->orWhereHas('companyContract', function ($search) use ($keyword) {
                    $search->whereRaw('LOWER(name) LIKE ?', ["%{$keyword}%"]);
                });
            });
        }
        return $query;
    }

    public function scopeFilter($query){

        $query = $query->when(request('company_id'), function ($query) {
                            $query->where('company_id',request('company_id'));
                        })
                        ->when(request('severity_id'), function ($query) {
                            $query->where('severity_id',request('severity_id'));
                        });

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
    
    public function company(){
        return $this->hasOne(Company::class,'id','company_id');
    }

    public function companyContract(){
        return $this->hasOne(CompanyContract::class,'id','company_contract_id');
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