<?php

namespace App\Models;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Model;

class ActionCode extends BaseModel 
{
    protected $table = 'action_codes';

    protected $fillable = [ 
        'name',
        'nickname',
        'description',
        'is_active',
        'send_email',
        'email_recipient_id',
        'skip_penalty'
    ];

    const INITIAL = 'INIT';
    const ESCALATE = 'ESCL';
    const ACTR = 'ACTR';
    const CLOSED = 'CLSD';
    const UPDATE = 'UPDT';
    const RESOLVED = 'RSLVD';


    const SEND_TO_COMPLAINT = 1;
    const SEND_TO_GROUP = 2;
    const SEND_TO_GROUP_BCC = 3;


    public function emailRecipientDescription(){
        return $this->hasOne(RefTable::class,'ref_code','email_recipient_id')->where('code_category', 'action_code_email_recipient');
    }
}
