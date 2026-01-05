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
        'skip_penalty',
        'role_id'
    ];
    
    const INITIAL = 'INIT';
    const ESCALATE = 'ESCL';
    const ACTR = 'ACTR';
    const UPDATE = 'UPDT';
    const VERIFY = 'VRFY';
    const PROG = 'PROG';
    const RETURN = 'RETURN';
    const DISC = 'DISC';
    const ONSITE = 'ONSITE';
    const STARTD = 'STARTD';
    const STOPD = 'STOPD';
    const CLOSED = 'CLSD';

    const SEND_TO_COMPLAINT = 1;
    const SEND_TO_GROUP = 2;
    const SEND_TO_GROUP_BCC = 3;

    protected array $filterable = ['name','nickname','description','is_active'];

    public function emailRecipientDescription(){
        return $this->hasOne(RefTable::class,'ref_code','email_recipient_id')->where('code_category', 'action_code_email_recipient');
    }

    public function getRoleDesc($role_id){
        $role_id = isset($role_id) ? json_decode($role_id,true) : []; 
        
        $data = Role::whereIn('id', $role_id)
                        ->pluck('name');  
        return $data;
    }
}
