<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Incident extends Model
{
    public $timestamps = false;

    public $incrementing = false;

    protected $table = 'HD_Case_Master';

    protected $primaryKey = 'cm_log_no';

    
}
