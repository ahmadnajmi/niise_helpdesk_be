<?php

namespace App\Models;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Model;

class SsoSession extends BaseModel
{
    protected $table = 'sso_sessions';

    protected $fillable = [
        'user_id',
        'id_token',
        'access_token',
        'is_active',
    ];  
}