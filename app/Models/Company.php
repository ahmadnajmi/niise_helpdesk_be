<?php

namespace App\Models;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Model;

class Company extends BaseModel
{
    protected $table = 'companies';

    protected $fillable = [ 
        'name',
        'nickname',
        'email',
        'phone_no',
        'address',
        'postcode',
        'city',
        'state_id',
        'fax_no',
        'is_active',
    ];

    protected array $filterable = ['name','nickname','phone_no','email','is_active'];

    public function stateDescription(){
        return $this->hasOne(RefTable::class,'ref_code','state_id')->where('code_category', 'state');
    }

    public function contract(){
        return $this->hasMany(CompanyContract::class,'company_id','id');
    }
    
}
