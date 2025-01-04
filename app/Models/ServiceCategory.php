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

}
