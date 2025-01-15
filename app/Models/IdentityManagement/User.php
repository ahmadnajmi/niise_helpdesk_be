<?php

namespace App\Models\IdentityManagement;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{

    protected $connection = 'oracle_identity_management'; 

    protected $table = 'users';

    protected $fillable = [
        'name',
        'position',
        'location',
        'email',
        'phone_no',
        'category_office'
    ];


    
}
