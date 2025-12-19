<?php

namespace App\Models;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Model;

class FailedJob extends BaseModel
{
    protected $table = 'failed_jobs';
    public $timestamp = false;
}
