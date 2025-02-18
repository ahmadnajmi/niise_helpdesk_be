<?php

namespace App\Models\IdentityManagement;

use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    //
    protected $connection = 'oracle_identity_management'; 
    protected $table = 'branch';

    protected $fillable = [
        'name',
        'state',
        'location',

    ];
}
