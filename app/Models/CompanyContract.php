<?php

namespace App\Models;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Model;

class CompanyContract extends BaseModel
{
    protected $fillable = [ 
        'name',
        'contractor_id',
        'start_date',
        'end_date',
        'company_id',
        'is_active',
    ];

    protected $casts = [
        'start_date' => 'datetime:Y-m-d',
        'end_date' => 'datetime:Y-m-d',
    ];

    public function company(){
        return $this->belongsTo(Company::class, 'company_id');
    }
}
