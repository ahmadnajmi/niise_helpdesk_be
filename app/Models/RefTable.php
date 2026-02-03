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

    protected array $filterable = ['code_category','name_en','name'];

    const PDF = 1;
    const CSV = 2;
    const SEVERITY_CRITICAL = 1;
    const SEVERITY_IMPORTANT = 2;
    const SEVERITY_MEDIUM = 3;
    const USER_TYPE_USER = 1;
    const USER_TYPE_GROUP_EMAIL = 2;

    
    public function incidentsStatus(){
        return $this->hasMany(Incident::class, 'status', 'ref_code');
    }

    public function getTranslatedNameAttribute(){
        $locale = request()->header('Accept-Language', 'en');
        
        return $this->{"name_{$locale}"} ?? $this->name;
    }

    public function getParentDesc($code_category,$refcode){
        $query = RefTable::where('code_category',$code_category)->where('ref_code',$refcode)->first();

        return $query?->name_en;
    }
}
