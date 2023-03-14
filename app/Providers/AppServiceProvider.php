<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Account;
use App\Models\ScheduleSetting;
use App\Models\Faculty;
use App\Models\Department;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->register(\L5Swagger\L5SwaggerServiceProvider::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Account::deleted(function ($account) {
            $account->schedule_settings()->delete();
            $account->faculties()->delete();
            $account->teachers()->delete();
        });

        Account::restored(function($account) {
            $account->schedule_settings()->withTrashed()->restore();
            $account->faculties()->withTrashed()->restore();
            $account->teachers()->withTrashed()->restore();
        });

        ScheduleSetting::deleted(function ($schedule_setting) {
            $schedule_setting->schedule_setting_items()->delete();
        });

        Faculty::deleted(function ($faculty) {
            $faculty->departments()->delete();
            $faculty->teachers()->delete();
            $faculty->subjects()->delete();
            $faculty->groups()->delete();
        });

        Faculty::restored(function ($faculty) {
            $faculty->departments()->withTrashed()->restore();
            $faculty->teachers()->withTrashed()->restore();
            $faculty->subject()->withTrashed()->restore();
            $faculty->groups()->withTrashed()->restore();
        });

        Department::deleted(function ($department) {
            $department->groups()->delete();
            $department->teachers()->delete();
            $department->subjects()->delete();
        });

        Department::restored(function ($department) {
            $department->groups()->withTrashed()->restore();
            $department->teachers()->withTrashed()->restore();
            $department->subjects()->withTrashed()->restore();
        });
    }
}
