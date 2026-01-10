<?php

namespace App\Observers;

use App\Models\Company;

class CompanyObserver
{
    public function created(Company $company)
    {
        Cache::forget('dynamic_option_company');
    }

    public function updated(Company $company)
    {
        Cache::forget('dynamic_option_company');
    }

    public function deleted(Company $company)
    {
        Cache::forget('dynamic_option_company');
    }
}
