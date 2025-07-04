<?php

namespace App\Models;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Model;

class Complaint extends BaseModel
{
    protected $table = 'complaint';

    protected $fillable = [ 
        'name',
        'email',
        'phone_no',
        'office_phone_no',
        'extension_no',
    ];
}
 
        