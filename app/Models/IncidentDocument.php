<?php

namespace App\Models;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class IncidentDocument extends BaseModel
{
    protected $table = 'incident_document';

    protected $fillable = [ 
        'incident_id',
        'type',
        'path',
    ];

    const APPENDIX = 1;
    const ASSET = 2;

    public function incident(){
        return $this->hasOne(Incident::class,'id','incident_id');
    }
}
