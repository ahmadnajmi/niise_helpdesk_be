<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Laravel\Passport\Passport;
use App\Observers\RoleObserver;
use App\Observers\SlaTemplateObserver;
use App\Observers\CompanyObserver;
use App\Models\Role;
use App\Models\SlaTemplate;
use App\Models\Company;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Passport::enablePasswordGrant();
        Role::observe(RoleObserver::class);
        SlaTemplate::observe(SlaTemplateObserver::class);
        Company::observe(CompanyObserver::class);
    }
}
