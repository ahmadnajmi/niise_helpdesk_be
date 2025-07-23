<?php

namespace App\Models;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Model;

class Sla extends BaseModel
{
    protected $table = 'sla';

    protected $fillable = [ 
        'code',
        'category_id',
        'branch_id',
        'start_date',
        'end_date',
        'sla_template_id',
        'group_id',
        'loaner',
        'loaner_type',
        'penalty_id',
        'is_active'
    ];

    protected $casts = [
        'start_date' => 'datetime:Y-m-d',
        'end_date' => 'datetime:Y-m-d',
    ];

    public function scopeSearch($query, $keyword){
        if (!empty($keyword)) {
            $query->where(function($q) use ($keyword) {
                $q->where('sla.code', 'like', "%$keyword%");
              
                $q->orWhereHas('category', function ($search) use ($keyword) {
                    $search->where('categories.name', 'like', "%$keyword%");
                });

                $q->orWhereHas('branch', function ($search) use ($keyword) {
                    $search->where('branch.name', 'like', "%$keyword%");
                });

                $q->orWhereHas('slaTemplate', function ($search) use ($keyword) {
                    $search->whereHas('severityDescription', function ($search) use ($keyword) {
                        $search->where('ref_table.name','like', "%$keyword%");
                    });
                });
            });
        }
        return $query;
    }

    public function scopeSortByField($query, $fields){

        if(isset($fields)){
            foreach($fields as $column => $order_by){
                if($column == 'severity_id'){
                    $query->leftJoin('sla_template', 'sla_template.id', '=', 'sla.sla_template_id')
                        ->leftJoin('ref_table', 'sla_template.severity_id', '=', 'ref_table.ref_code')->where('ref_table.code_category','severity')
                        ->orderBy('ref_table.name', $order_by);
                }
                else{
                    $query->orderBy('sla.'.$column,$order_by);
                }
            }
        }
       
        
        return $query;
    }

    public function stateDescription(){
        return $this->hasOne(RefTable::class,'ref_code','state_id')->where('code_category', 'state');
    }

    public function branch(){
        return $this->hasOne(Branch::class,'id','branch_id');
    }

    public function slaTemplate(){
        return $this->hasOne(SlaTemplate::class,'id','sla_template_id');
    }

    public function group(){
        return $this->hasOne(Group::class,'id','group_id');
    }

     public function category(){
        return $this->hasOne(Category::class,'id','category_id');
    }

    public function penaltyDescription(){
        return $this->hasOne(RefTable::class,'ref_code','penalty_id')->where('code_category', 'penalty_price');
    }

    public function loanerTypeDescription(){
        return $this->hasOne(RefTable::class,'ref_code','loaner_type')->where('code_category', 'sla_type');
    }

    public function getBranchDesc($branch_id){
        $branch_id = json_decode($branch_id,true);  

        $data = Branch::select('id','state_id','name')
                        ->with(['stateDescription' => function ($query) {
                            $query->select('ref_code','name','name_en');
                        }])
                        ->whereIn('id', $branch_id)
                        ->get();  

        return $data;
    }
}
