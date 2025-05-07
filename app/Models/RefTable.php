<?php

namespace App\Models;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Model;

class RefTable extends BaseModel
{
    protected $table = 'ref_table';

    protected $fillable = [ 
        'code_category',
        'ref_code',
        'name_en',
        'name',
    ];

    public function createdBy(){
        return $this->hasOne(User::class,'id','created_by');
    }

    public function updatedBy(){
        return $this->hasOne(User::class,'id','updated_by');
    }

    public function scopeFilter($query){

        if (request('code_category')) {
            $codeCategory = request('code_category');

            if (is_array($codeCategory)) {
                $query->whereIn('code_category', $codeCategory);
            } else {
                $query->where('code_category', $codeCategory);
            }
        }

      

        return $query;
    }
}
