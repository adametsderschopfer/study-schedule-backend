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
        });

        Account::restored(function($account) {
            $account->schedule_settings()->withTrashed()->restore();
            $account->faculties()->withTrashed()->restore();
        });

        ScheduleSetting::deleted(function ($schedule_setting) {
            $schedule_setting->schedule_setting_items()->delete();
        });

        Faculty::deleted(function ($faculty) {
            $faculty->departments()->delete();
        });

        Faculty::restored(function ($faculty) {
            $faculty->departments()->withTrashed()->restore();
        });

        Department::deleted(function ($department) {
            $department->department_subjects()->delete();
            $department->department_groups()->delete();
            $department->department_teachers()->delete();
        });

        Department::restored(function ($department) {
            $department->department_subjects()->withTrashed()->restore();
            $department->department_groups()->withTrashed()->restore();
            $department->department_teachers()->withTrashed()->restore();
        });
    }
}
