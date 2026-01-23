<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Laravel\Passport\Passport;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Passport::useClientModel(Client::class);
        // Passport::useTokenModel(Token::class);
        // Passport::tokensExpireIn(now()->addMinutes(5));
        // Passport::refreshTokensExpireIn(now()->addDays(60));

    }
}
