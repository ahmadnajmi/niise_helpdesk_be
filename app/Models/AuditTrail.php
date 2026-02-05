<?php

namespace App\Models;

use OwenIt\Auditing\Models\Audit as BaseAudit;
use Illuminate\Support\Str;

class AuditTrail extends BaseAudit
{
    protected $table = 'audits';

    protected $filterable = ['time_created_at','date_created_at', 'ic_no'];
    protected $sortable = [
        'created_at',
        'ic_no',
        'event'
    ];

    public function scopeSearch($query, $keyword)
    {
        if (!$keyword)   return $query;

        return $query->where(function ($q) use ($keyword) {
            $q->orWhereRaw('LOWER("EVENT") LIKE ?', ['%' . strtolower($keyword) . '%']);
            $q->orWhereDate('created_at', 'like', "%{$keyword}%");
            $q->orWhereHas('user', function ($query)use($keyword) {
                $query->where('ic_no', 'like', '%' . strtolower($keyword) . '%');
            });
        });
    }

    public function scopeFilter($query){
        foreach ($this->filterable as $field) {
            if (!request()->has($field)) continue;

            $value = request($field);

            if ($field === 'time_created_at') {
                $query->whereRaw('TO_CHAR("CREATED_AT", \'HH24:MI\') = ?', [$value]);
            }
            elseif($field == 'date_created_at'){
                $query->whereDate('created_at', $value);
            }
            elseif($field == 'ic_no'){
                $query->whereHas('user', function ($query)use($value) {
                        $query->where('ic_no','like', '%' . strtolower($value) . '%');
                    });
            }
            else{
                $query->where($field, $value);
            }
        }
        return $query;
    }

    public function scopeSortByField($query, $request){
        $hasSorting = false;

        $sortableFields = $this->sortable;

        foreach ($request->all() as $key => $direction) {

            if (Str::endsWith($key, '_sort')) {
                $field = str_replace('_sort', '', $key);

                $direction = strtolower($direction);

                if (!in_array($direction, ['asc', 'desc'])) continue;

                $hasSorting = true;

                if($field == 'ic_no'){
                    $query->leftJoin('users', 'users.id', '=', 'audits.user_id')
                            ->select('audits.*')
                            ->orderBy("users.ic_no", $direction);
                }
                elseif($field == 'time_created_at'){
                   $query->orderByRaw('TO_CHAR(created_at, \'HH24:MI:SS\') ' . $direction);
                }
                elseif($field == 'date_created_at'){
                    $query->orderByRaw('TO_CHAR(created_at, \'YYYY-MM-DD\') ' . $direction);
                }
                else{
                    $query->orderBy($field, $direction);
                }

            }
        }

        if (!$hasSorting && $this->timestamps) {
            $query->orderByDesc('updated_at');
        }

        return $query;
    }

    // Relationship example (optional)
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
