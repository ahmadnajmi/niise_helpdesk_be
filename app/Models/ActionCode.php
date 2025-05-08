<?php

namespace App\Models;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class ActionCode extends BaseModel 
{
    protected $table = 'action_codes';

    protected $fillable = [ 
        'name',
        'nickname',
        'description',
        'is_active',
        'send_email',
        'email_recipient_id'
    ];

    public function emailRecipientDescription(){
        return $this->hasOne(RefTable::class,'ref_code','email_recipient_id')->where('code_category', 'action_code_email_recipient');
    }
}
