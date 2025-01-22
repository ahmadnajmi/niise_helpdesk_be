<?php

namespace App\Models\IdentityManagement;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{

    protected $connection = 'oracle_identity_management'; 

    protected $table = 'users';

    protected $fillable = [
        'id',
        'name',
        'position',
        'branch_id',
        'email',
        'phone_no',
        'category_office'
    ];


    
}
