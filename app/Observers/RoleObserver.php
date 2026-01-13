<?php

namespace App\Observers;

use Illuminate\Support\Facades\Cache;
use App\Models\Role;

class RoleObserver
{
    public function created(Role $role)
    {
        Cache::forget('dynamic_option_role');
    }

    public function updated(Role $role)
    {
        Cache::forget('dynamic_option_role');
    }

    public function deleted(Role $role)
    {
        Cache::forget('dynamic_option_role');
    }
}
