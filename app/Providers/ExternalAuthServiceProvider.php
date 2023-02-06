<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\DTO\ExternalAccount;
use App\Services\ExternalAccountService;
use Illuminate\Support\Facades\App;

class ExternalAuthServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        // App::singletone(ExternalAccount::class, function() {
        //     return new ExternalAccount();
        // });
        App::singleton(ExternalAccountService::class, function ($app) {
            return new ExternalAccount();
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
