<?php

namespace App\Models;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Model;
use App\Enums\RefTableReceivedBy;
use App\Enums\RefTableBranchType;

class RefTable extends BaseModel
{
    protected $table = 'ref_table';

    protected $fillable = [ 
        'code_category',
        'ref_code',
        'name_en',
        'name',
        'received_by',
        'is_active',
        'branch_type'
    ];

    protected $casts = [
        'received_by' => RefTableReceivedBy::class, 
        'branch_type' => RefTableBranchType::class,
    ];

    public function createdBy(){
        return $this->hasOne(User::class,'id','created_by');
    }

    public function updatedBy(){
        return $this->hasOne(User::class,'id','updated_by');
    }

    public function getReceivedByDescriptionAttribute(): string{
        return $this->received_by?->label() ?? null;
    }

    public function getBranchTypeDescriptionAttribute(): string{
        return $this->branch_type?->label() ?? null;
    }


    public function scopeFilter($query){

        if (request('code_category')) {
            $query->whereIn('code_category',request('code_category'));
        }

        return $query;
    }
}
