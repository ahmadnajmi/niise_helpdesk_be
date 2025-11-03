<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Schema; 
use OwenIt\Auditing\Contracts\Auditable;
use App\Models\User;
use Illuminate\Support\Str;

class BaseModel extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    // protected $commonFillable = ['created_by', 'updated_by'];
    protected $commonFillable = [];
    protected static $sortable = [];
    protected array $searchable = [];
    protected array $filterable = [];

    public function __construct(array $attributes = []) {
        parent::__construct($attributes);

        $this->mergeFillable($this->commonFillable);
    }

    // public function scopeSearch($query, $keyword){
    //     if (!empty($keyword) && !empty($this->searchable)) {
    //         $keyword = strtolower($keyword);
    //         $query->where(function ($q) use ($keyword) {
    //             foreach ($this->searchable as $column) {
    //                 $q->orWhereRaw("LOWER({$column}) LIKE ?", ["%{$keyword}%"]);
    //             }
    //         });
    //     }
    //     return $query;
    // }

    public function scopeSearch($query, $keyword){
        if (empty($keyword)) return $query;

        $fields = property_exists($this, 'searchable') && !empty($this->searchable)  ? $this->searchable   : (property_exists($this, 'filterable') ? $this->filterable : []);

        $fields = array_filter($fields, fn($f) => $f !== 'is_active');

        if (empty($fields)) return $query;

        $keyword = strtolower($keyword);

        $query->where(function ($q) use ($fields, $keyword) {
            foreach ($fields as $column) {
                
                if ($column === 'state_id' && Schema::hasColumn($this->getTable(), $column)) {
                    $type = Schema::getColumnType($this->getTable(), $column);
                    if ($type === 'clob') {
                        continue; // skip this column
                    }
                }
                
                $q->orWhereRaw("LOWER({$column}) LIKE ?", ["%{$keyword}%"]);
            }
        });

        return $query;
    }

    public function scopeFilter($query){
        foreach ($this->filterable as $field) {
            if (!request()->has($field)) continue;

            $value = request($field);

            if ($field === 'state_id') {
                $stateIds = is_array($value) ? $value : [$value];

                $query->where(function ($q) use ($stateIds) {
                    foreach ($stateIds as $id) {
                        $q->orWhereRaw(
                            "EXISTS (
                                SELECT 1 FROM JSON_TABLE(
                                    state_id, '$[*]' 
                                    COLUMNS (value NUMBER PATH '$')
                                ) jt 
                                WHERE jt.value = TO_NUMBER(?)
                            )",
                            [(string)$id] // cast to string to satisfy Oracle binding
                        );
                    }
                });
            }
            elseif(is_array($value)){
                $query->whereIn($field, $value);
            } 
            else {
                $query->where($field, $value);
            }
        }
        return $query;
    }

    // public function scopeSortByField($query, $request){
    //     $hasSorting = false;

    //     foreach ($request->all() as $key => $direction) {
    //         if (Str::endsWith($key, '_sort')) {
    //             $field = str_replace('_sort', '', $key);
    //             $direction = strtolower($direction);
    //             $sortable = static::$sortable[$field] ?? null;

    //             if (!in_array($direction, ['asc', 'desc']) || !$sortable) continue;

    //             $hasSorting = true;
    //             $query->orderBy($sortable, $direction);
    //         }
    //     }

    //     if (!$hasSorting && $this->timestamps) {
    //         $query->orderByDesc('updated_at');
    //     }

    //     return $query;
    // }

    public function scopeSortByField($query, $request){
        $hasSorting = false;

        $sortableFields = !empty(static::$sortable) ? static::$sortable : array_combine(
            property_exists($this, 'filterable') ? $this->filterable : [],
            property_exists($this, 'filterable') ? $this->filterable : []
        );

        foreach ($request->all() as $key => $direction) {
            if (Str::endsWith($key, '_sort')) {
                $field = str_replace('_sort', '', $key);
                $direction = strtolower($direction);
                $sortable = $sortableFields[$field] ?? null;

                if (!in_array($direction, ['asc', 'desc']) || !$sortable) continue;

                $hasSorting = true;
                $query->orderBy($sortable, $direction);
            }
        }

        if (!$hasSorting && $this->timestamps) {
            $query->orderByDesc('updated_at');
        }

        return $query;
    }

    public function mergeFillable(array $fields){
        $this->fillable = array_merge($this->fillable, $fields);
    }

    public function createdBy(){
        return $this->hasOne(User::class,'id','created_by');
    }

    public function updatedBy(){
        return $this->hasOne(User::class,'id','updated_by');
    }

    protected static function boot() {
        parent::boot();

        static::addGlobalScope('orderByUpdatedAt', function (Builder $builder) {
            $model = new static; 
            
            // if (Schema::hasColumn($model->getTable(), 'updated_at')) {
            //     $builder->orderBy($model->getTable()'.updated_at', 'desc');
            // }
        });

     
        static::creating(function ($model) {
            if(Schema::hasColumn($model->getTable(), 'created_by') && Schema::hasColumn($model->getTable(), 'updated_by')){
                if (auth()->check()) {
                    $model->created_by = auth()->user()->id;
                    $model->updated_by = auth()->user()->id;
                }
                else{
                    $model->created_by = 1;
                    $model->updated_by = 1;
                }
            }
            
        });

        static::updating(function ($model) {
            if(Schema::hasColumn($model->getTable(), 'updated_by')){
                if (auth()->check()) {
                    $model->updated_by = auth()->user()->id;
                }
                else{
                    $model->updated_by = 2;
                }
            }
        });
    }

    public function transformAudit(array $data): array {

        $skipModels = [
            \App\Models\Workbasket::class,
            \App\Models\Category::class,
            \App\Models\Customer::class,
        ];

        $class = null;
        
        switch ($data['auditable_type']) {
           
            case ActionCode::class:
                $class = 'Kod Tindakan';
                break;

            case Calendar::class:
                $class = 'Kalendar';
                break;

            case Category::class:
                $class = 'Kategori';
                break;

            case Company::class:
                $class = 'Kontraktor';
                break;

            case CompanyContract::class:
                $class = 'Kontrak';
                break;

            case EmailTemplate::class:
                $class = 'Templat Emel';
                break;

            case Group::class:
                $class = 'Kumpulan';
                break;

            case Incident::class:
                $class = 'Incident';
                break;

            case IncidentResolution::class:
                $class = 'Incident Penyelesaian';
                break;

            case KnowledgeBase::class:
                $class = 'Knowledge Base';
                break;

            case Module::class:
                $class = 'Modul';
                break;

            case OperatingTime::class:
                $class = 'Waktu Operasi';
                break;

            case Permission::class:
                $class = 'Kebenaran';
                break;

            case RefTable::class:
                $class = 'Tetapan Global';
                break;

            case Role::class:
                $class = 'Peranan';
                break;

            case RolePermission::class:
                $class = 'Pernanan Kebenaran';
                break;

            case Sla::class:
                $class = 'Sla';
                break;

            case SlaTemplate::class:
                $class = 'Templat SLA';
                break;

            case User::class:
                $class = 'Individu';
                break;

            case UserRole::class:
                $class = 'Peranan Individu';
                break;

            case UserGroup::class:
                $class = 'Kumpulan Individu';
                break;

            case UserGroup::class:
                $class = 'Kumpulan Individu Access';
                break;
        }

        switch ($data['event']) {
            case 'created':
                $action = 'Daftar ';
                break;

            case 'updated':
                $action = 'Kemaskini ';
                break;

            case 'deleted':
                $action = 'Padam ';
                break;
        }
        
        $data['event'] = $action.$class;
        
        return $data;
    }
}
