<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;
use App\Models\Setting;
use App\Models\Account;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to the "home" route for your application.
     *
     * Typically, users are redirected here after authentication.
     *
     * @var string
     */
    public const HOME = '/home';

    /**
     * Define your route model bindings, pattern filters, and other route configuration.
     *
     * @return void
     */
    public function boot()
    {
        $this->configureRateLimiting();

        $this->routes(function () {
            Route::middleware('api')
                ->prefix('api')
                ->group(base_path('routes/api.php'));

            Route::middleware('web')
                ->group(base_path('routes/web.php'));
        });

        $this->bindModels([
            'setting' => Setting::class,
        ]);
    }

    /**
     * Configure the rate limiters for the application.
     *
     * @return void
     */
    protected function configureRateLimiting()
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });
    }

    protected function bindModels(array $bindings)
    {
        $headers = getallheaders();
        
        foreach ($bindings as $key => $model) {
            $account = Account::where('external_id', $headers[Account::EXTERNAL_ACCOUNT_ID_HEADER_KEY] ?? null)->first();

            if (!$account) {
                return;
            }

            Route::model($key, $model);
            Route::bind($key, function ($id) use ($model, $account) {
                return $model::where('id', $id)
                        ->where('account_id', $account->getId())
                        ->first();
            });
        }
    }
}
