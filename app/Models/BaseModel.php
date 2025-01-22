<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BaseModel extends Model
{
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

    protected static function boot()
    {
        parent::boot();

     
        static::creating(function ($model) {
            if (auth()->check()) {
                $model->created_by = auth()->user()->id;
                $model->updated_by = auth()->user()->id;
            }
            else{
                $model->created_by = 1;
                $model->updated_by = 1;
            }
        });

        static::updating(function ($model) {
            if (auth()->check()) {
                $model->updated_by = auth()->user()->id;
            }
            else{
                $model->updated_by = 2;
            }
        });
    }
}
