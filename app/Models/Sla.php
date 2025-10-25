<?php

namespace App\Models;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

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

    protected static $sortable = [
        'category' => 'category.name',
        'code' => 'code',
        'severity' => 'slaTemplate.severityDescription.name',
        'is_active' => 'is_active'
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

    public function scopeFilter($query){

        $query = $query->when(request('category_id'), function ($query) {
                            $query->where('category_id',request('category_id'));
                        })
                        ->when(request('branch_id'), function ($query) {
                            $branchId = request('branch_id'); // single value
                            $query->whereRaw(
                                "EXISTS (
                                    SELECT 1 FROM JSON_TABLE(
                                        branch_id, '$[*]' 
                                        COLUMNS (value NUMBER PATH '$')
                                    ) jt 
                                    WHERE jt.value = ?
                                )", 
                                [$branchId]
                            );
                        })
                        ->when(request('severity_id'), function ($query) {
                            $query->WhereHas('slaTemplate', function ($query)  {
                                $query->where('severity_id',request('severity_id'));
                            });
                        })
                        ->when(request()->has('is_active'), function ($query) {
                            $query->where('sla.is_active',request('is_active') == true ? true : false);
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

                if (str_contains($sortable, '.')) {
                    [$relation, $column] = explode('.', $sortable);

                    if($field === 'severity') {
                        $lang = substr(request()->header('Accept-Language'), 0, 2); 

                        $query->leftJoin('sla_template', 'sla_template.id', '=', 'sla.sla_template_id')
                                ->leftJoin('ref_table', function ($join) {
                                $join->on('ref_table.ref_code', '=', 'sla_template.severity_id')
                                    ->where('ref_table.code_category', '=', 'severity');
                                })
                                ->orderByRaw("
                                    LOWER(CASE 
                                        WHEN ? = 'ms' THEN ref_table.name 
                                        ELSE ref_table.name_en 
                                    END) {$direction}
                                ", [$lang]);
                    }
                    elseif($field =='category'){
                        $query->leftJoin('categories', 'categories.id', '=', 'sla.category_id')
                            ->select('sla.*')
                            ->orderBy("categories.$column", $direction);
                    }
                } 
               
                else {
                    
                    $query->orderBy($sortable, $direction);
                }
            }
        }

        if (!$hasSorting) {
            $query->orderByDesc('updated_at');
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
