<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Schema; 
use OwenIt\Auditing\Contracts\Auditable;
use App\Models\User;

class BaseModel extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    protected $commonFillable = ['created_by', 'updated_by'];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->mergeFillable($this->commonFillable);
    }

    public function mergeFillable(array $fields)
    {
        $this->fillable = array_merge($this->fillable, $fields);
    }

    public function createdBy(){
        return $this->hasOne(User::class,'id','created_by');
    }

    public function updatedBy(){
        return $this->hasOne(User::class,'id','updated_by');
    }

    protected static function boot()
    {
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

        $class = null;
        
        switch ($data['auditable_type']) {
            case UserGroup::class:
                $class = 'Kumpulan Individu';
                break;

            case ActionCode::class:
                $class = 'Kod Tindakan';
                break;

            case Calendar::class:
                $class = 'Kalendar';
                break;

            case Category::class:
                $class = 'Kategori';
                break;

            case EmailTemplate::class:
                $class = 'Templat Emel';
                break;

            case Group::class:
                $class = 'Kumpulan';
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

            case SlaCategory::class:
                $class = 'SLA Kategori';
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
