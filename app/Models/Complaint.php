<?php

namespace App\Models;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Complaint extends BaseModel
{
    use HasFactory;
    protected $table = 'complaint';

    protected $fillable = [ 
        'name',
        'email',
        'phone_no',
        'office_phone_no',
        'extension_no',
    ];
}
 
        