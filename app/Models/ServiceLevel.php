<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServiceLevel extends Model
{
    public $timestamps = false;

    public $incrementing = false;

    protected $table = 'HD_SLA';

    protected $primaryKey = 'sl_sla_code';
}
