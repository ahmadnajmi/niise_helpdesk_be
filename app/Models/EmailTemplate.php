<?php

namespace App\Models;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Model;

class EmailTemplate extends BaseModel
{
    protected $table = 'email_templates';

    protected $fillable = [ 
        'name',
        'sender_name',
        'sender_email',
        'notes',
        'is_active',
    ];

    protected array $filterable = ['name','sender_name','sender_email','notes','is_active'];

}
