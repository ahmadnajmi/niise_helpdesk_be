<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ServiceCategory extends Model
{
    public $timestamps = false;

    public $incrementing = false;

    protected $table = 'refCategory';

    protected $primaryKey = 'Ct_Code';

    public $keyType = 'string';

    public function irel_RefCategoryParent() {
        return $this->belongsTo(ServiceCategory::class,'Ct_Parent','Ct_Code');
    }

    public function getMaxParent($parent){
        return $this->select('Ct_Code')
                    ->where('Ct_Parent',$parent)
                    ->max('Ct_Code');
    }
 public function getMaxLevel($level) {
        return $this->selectRaw('MAX(CAST(Ct_Code AS SIGNED)) AS maxCtCode')
                    ->where('Ct_Level', $level)
                    ->value('maxCtCode');
    }
    public function getCategory($id){
        return $this->where('Ct_Code',$id)
                    ->with('irel_RefCategoryParent')
                    ->get();
    }

}
