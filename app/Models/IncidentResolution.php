<?php

namespace App\Models;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class IncidentResolution extends BaseModel
{
    protected $table = 'incident_resolution';

    protected $fillable = [ 
        'incident_id',
        'group_id',
        'operation_user_id',
        'report_contractor_no',
        'action_codes',
        'notes',
        'solution_notes',
    ];

    protected static $sortable = [
        'action_code' => 'action_codes.nickname',
        'solution_notes' => 'solution_notes',
        'created_at' => 'created_at',
        'created_by' => 'user.name',
    ];

    public function actionCodes(){
        return $this->hasOne(ActionCode::class,'nickname','action_codes');
    }

    public function incident(){
        return $this->hasOne(Incident::class,'id','incident_id');
    }

    public function group(){
        return $this->hasMany(Group::class,'id','group_id');
    }

    public function scopeSortByField($query,$request){
     
        foreach ($request->all() as $key => $direction) {

            if (Str::endsWith($key, '_sort')) {

                $field = str_replace('_sort', '', $key);
                $direction = strtolower($direction);
                $sortable = static::$sortable[$field] ?? null;

                if (!in_array($direction, ['asc', 'desc']) || !$sortable) {
                    continue;
                }
                
                if (str_contains($sortable, '.')) {
                    [$relation, $column] = explode('.', $sortable);
                    
                    if($field === 'action_code') {
                        $query->leftJoin('action_codes', 'action_codes.nickname', '=', 'incident_resolution.action_codes')
                            ->select('incident_resolution.*')
                            ->orderBy("action_codes.$column", $direction);
                    }
                    elseif($field === 'created_by') {
                        $query->leftJoin('user', 'user.id', '=', 'incident_resolution.created_by')
                            ->select('incident_resolution.*')
                            ->orderBy("user.$column", $direction);
                    }
                } 
               
                else {
                    $query->orderBy($sortable, $direction);
                }
            }
        }

        return $query;
    }
}
