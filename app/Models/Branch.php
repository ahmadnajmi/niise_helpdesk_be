<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use DB;

class Branch extends Model
{
    //
    protected $table = 'branch';

    public $incrementing = false;

    protected $fillable = [
        'id',
        'name',
        'state_id',
        'category',
        'location',
    ];

    
    public function scopeSearch($query, $keyword){
        if (!empty($keyword)) {
            $keyword = strtolower($keyword);
            
            $query->where(function($q) use ($keyword) {
                $q->whereRaw('LOWER(branch.name) LIKE ?', ['%' . strtolower($keyword) . '%']);

                $q->orWhereHas('operatingTime', function ($search) use ($keyword) {
                    $search->whereRaw('LOWER(operating_times.operation_start) LIKE ?', ["%{$keyword}%"]);
                });

                $q->orWhereHas('operatingTime', function ($search) use ($keyword) {
                    $search->whereRaw('LOWER(operating_times.operation_end) LIKE ?', ["%{$keyword}%"]);
                });
            });
        }
        return $query;
    }

    public function scopeFilter($query){
        
        $query = $query->when(request('branch_id'), function ($query) {
                            $query->where('id',request('branch_id'));
                        })
                        ->when(request('operation_start'), function ($query) {
                            $query->WhereHas('operatingTime', function ($query)  {
                                $query->whereRaw("TO_CHAR(operation_start, 'HH24:MI') = ?", [request('operation_start')]);
                            });
                        })
                        ->when(request('operation_end'), function ($query) {
                            $query->WhereHas('operatingTime', function ($query)  {
                              $query->whereRaw("TO_CHAR(operation_end, 'HH24:MI') = ?", [request('operation_end')]);
                            });
                        })
                        ->when(request()->has('is_active'), function ($query) {
                            $query->whereHas('operatingTime', function ($q) {
                                $q->where('is_active', request('is_active'));
                            });
                        });
                       

        return $query;
    }

    public function scopeSortByField($query,$request){
        $hasSorting = false;

        foreach ($request->all() as $key => $direction) {

            if (Str::endsWith($key, '_sort')) {
                
                $field = str_replace('_sort', '', $key);
                $direction = strtolower($direction);
                $sortable = static::$sortable[$field] ?? null;

                if (!in_array($direction, ['asc', 'desc']) || !$sortable) {
                    continue;
                }
                $hasSorting = true;

              
                $query->orderBy($sortable, $direction);
            }
        }

        if (!$hasSorting) {
            $query->orderByDesc('updated_at');
        }

        return $query;
    }

    public function operatingTime(){
        return $this->hasMany(OperatingTime::class, 'branch_id','id');
    }

    public function stateDescription(){
        return $this->hasOne(RefTable::class,'ref_code','state_id')->where('code_category', 'state');
    }

    public function incidents(){
        return $this->hasMany(Incident::class, 'branch_id','id');
    }
}
