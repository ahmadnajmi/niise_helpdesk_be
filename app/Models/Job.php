<?php

namespace App\Models;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Model;

class Job extends BaseModel
{
    protected $table = 'jobs';
    public $timestamp = false;
}
