<?php

namespace App\Models;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class LogExternalApi extends BaseModel
{
    protected $table = 'log_external_api';

    protected $fillable = [ 
        'service_name',
        'endpoint',
        'is_success',
        'status_code',
        'request',
        'response',
        'error_message',
    ];

    const ASSET = 'Asset';
    const JASPER = 'Jasper';
    const EMAIL_ACTION_CODE = 'Email Action Code';
    const EMAIL_FORGET_PASSWORD = 'Email Forget Password';
}
