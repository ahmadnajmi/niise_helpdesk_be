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
}
