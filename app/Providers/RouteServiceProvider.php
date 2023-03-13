<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;
use App\Models\Account;
use App\Models\ScheduleSetting;
use App\Models\Faculty;
use App\Models\Department;
use App\Models\DepartmentGroup;
use App\Models\Teacher;
use App\Models\Schedule;
use App\Models\Subject;
use App\Models\Group;

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
            [
                'key' => 'scheduleSetting',
                'model' => ScheduleSetting::class,
                'isThrough' => false
            ],
            [
                'key' => 'faculty',
                'model' => Faculty::class,
                'isThrough' => false
            ],
            [
                'key' => 'department',
                'model' => Department::class,
                'isThrough' => true
            ],
            [
                'key' => 'department_group',
                'model' => DepartmentGroup::class,
                'isThrough' => true
            ],
            [
                'key' => 'teacher',
                'model' => Teacher::class,
                'isThrough' => false
            ],
            [
                'key' => 'schedule',
                'model' => Schedule::class,
                'isThrough' => false
            ],
            [
                'key' => 'subject',
                'model' => Subject::class,
                'isThrough' => false
            ]
            ,
            [
                'key' => 'group',
                'model' => Group::class,
                'isThrough' => false
            ]
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
        
        foreach ($bindings as $binding) {
            $account = Account::where('external_id', $headers[Account::EXTERNAL_ACCOUNT_ID_HEADER_KEY] ?? null)->first();

            if (!$account) {
                return;
            }

            $key = $binding['key'];
            $model = $binding['model'];
            $isThrough = $binding['isThrough'];

            Route::model($key, $model);
            Route::bind($key, function ($id) use ($model, $account, $isThrough) {
                if ($isThrough) {
                    $entity = $model::where('id', $id)->first() ?? abort(404);
                    if ($entity->account->getId() == $account->getId()) {
                        return $entity;
                    }
                    abort(404);
                } else {
                    return $model::where('id', $id)
                            ->where('account_id', $account->getId())
                            ->first() ?? abort(404);
                }
            });
        }
    }
}
