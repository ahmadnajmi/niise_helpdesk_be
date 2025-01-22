<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    public $timestamps = false;

    public $incrementing = false;

    protected $table = 'HD_Report_Info';

    protected $primaryKey = 'ri_report_code';
}
