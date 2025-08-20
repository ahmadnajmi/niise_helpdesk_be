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

    const INIT = 'INIT';
    const ESCL = 'ESCL';
    const ACTR = 'ACTR';
    const CLSD = 'CLSD';
    const UPDT = 'UPDT';

    const SEND_TO_COMPLAINT = 1;
    const SEND_TO_GROUP = 2;
    const SEND_TO_GROUP_BCC = 3;


    public function emailRecipientDescription(){
        return $this->hasOne(RefTable::class,'ref_code','email_recipient_id')->where('code_category', 'action_code_email_recipient');
    }
}
