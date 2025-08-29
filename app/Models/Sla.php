<?php

namespace App\Models;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Sla extends BaseModel
{
    use HasFactory;
    protected $table = 'sla';

    protected $fillable = [ 
        'code',
        'category_id',
        'branch_id',
        'sla_template_id',
        'group_id',
        'is_active'
    ];

    protected static function booted(){
        static::creating(function ($model) {
            $get_sla = Sla::where('category_id',$model->category_id)->orderBy('code','desc')->first();

            $category = Category::find($model->category_id);

            if($get_sla){
                $code = $get_sla->code;

                $old_code = substr($code, -2);

                $next_number = str_pad($old_code + 1, 2, '0', STR_PAD_LEFT); // "06"
            }
            else{
                $next_number = '01';
            }

            $model->code = strtoupper($category->name).$next_number;
        });
    }


    public function scopeSearch($query, $keyword){
        if (!empty($keyword)) {
            $keyword = strtolower($keyword);

            $query->where(function($q) use ($keyword) {
                $q->whereRaw('LOWER(sla.code) LIKE ?', ["%{$keyword}%"]);

                $q->orWhereHas('category', function ($search) use ($keyword) {
                    $search->whereRaw('LOWER(categories.name) LIKE ?', ["%{$keyword}%"]);
                });

                // $q->orWhereHas('branch', function ($search) use ($keyword) {
                //     $search->whereRaw('LOWER(branch.name) LIKE ?', ["%{$keyword}%"]);
                // });

                $q->orWhereHas('slaTemplate', function ($search) use ($keyword) {
                    $search->whereHas('severityDescription', function ($search) use ($keyword) {
                        $search->whereRaw('LOWER(ref_table.name) LIKE ?', ["%{$keyword}%"]);
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

    public function getBranchDetails($branch_id){
        $branch_id = json_decode($branch_id,true);  

        $data = Branch::select('id','state_id','name')
                        ->with(['stateDescription' => function ($query) {
                            $query->select('ref_code','name','name_en');
                        }])
                        ->whereIn('id', $branch_id)
                        ->get();  

        return $data;
    }

    public function getBranchDesc($branch_id){
        $branch_id = json_decode($branch_id,true);  

        $data = Branch::whereIn('id', $branch_id)
                        ->pluck('name');  

        return $data;
    }
}
