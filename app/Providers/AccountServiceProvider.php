<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\DTO\AccountRepository;
use App\Services\AccountService;
use Illuminate\Support\Facades\App;

class AccountServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        App::singleton(AccountService::class, function ($app) {
            return new AccountRepository();
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
