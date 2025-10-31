<?php

namespace App\Models;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Group extends BaseModel
{
    protected $table = 'groups';

    protected $fillable = [ 
        'name',
        'description',
        'is_active',
    ];

    protected static $sortable = [
        'name' => 'name',
        'is_active' => 'is_active'
    ];

    protected array $filterable = ['name','is_active'];


    public function scopeSearch($query, $keyword){
        if (!empty($keyword)) {
            $keyword = strtolower($keyword);
            
            $query->where(function($q) use ($keyword) {
                $q->orWhereRaw('LOWER(groups.name) LIKE ?', ["%{$keyword}%"]);
            });
        }
        return $query;
    }

    public function scopeFilter($query){

        $query = $query->when(request('name'), function ($query) {
                            $query->where('name',request('name'));
                        })
                        ->when(request()->has('is_active'), function ($query) {
                            $query->where('is_active',request('is_active') == true ? true : false);
                        });

        return $query;
    }

    public function scopeSortByField($query,$request){
        $hasSorting = false;

        foreach ($request->all() as $key => $direction) {

            if (Str::endsWith($key, '_sort')) {
                
                $field = str_replace('_sort', '', $key);
                $direction = strtolower($direction);
                $sortable = static::$sortable[$field] ?? null;

                if (!in_array($direction, ['asc', 'desc']) || !$sortable) {
                    continue;
                }
                $hasSorting = true;

              
                $query->orderBy($sortable, $direction);
            }
        }

        if (!$hasSorting) {
            $query->orderByDesc('updated_at');
        }

        return $query;
    }


    public function userGroup(){
        return $this->hasMany(UserGroup::class,'groups_id');
    }

    public function userGroupAccess(){
        return $this->hasMany(UserGroupAccess::class,'groups_id','id');
    }

    public function users(){
        return $this->hasManyThrough(User::class, UserGroup::class, 'groups_id', 'ic_no', 'id', 'ic_no');
    }
}
