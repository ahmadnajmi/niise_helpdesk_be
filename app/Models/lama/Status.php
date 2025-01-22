<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    public $timestamps = false;

    public $incrementing = false;

    protected $table = 'com_status';

    protected $primaryKey = 'ID';

}
