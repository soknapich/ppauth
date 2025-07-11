<?php

namespace App\Providers;

use Carbon\Carbon;
use Illuminate\Support\ServiceProvider;
use Laravel\Passport\Passport;

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
        //Passport::hashClientSecrets();
        Passport::tokensExpireIn(Carbon::now()->addYear());
        Passport::refreshTokensExpireIn(Carbon::now()->addYear());
        Passport::personalAccessTokensExpireIn(Carbon::now()->addYear());
        //Passport::enablePasswordGrant();
    }
}
