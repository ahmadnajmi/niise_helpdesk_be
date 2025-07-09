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
        'ref_code_parent',
    ];

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

    public function getParentDesc($code_category,$refcode){
        $query = RefTable::where('code_category',$code_category)->where('ref_code',$refcode)->first();

        return $query?->name_en;
    }
}
