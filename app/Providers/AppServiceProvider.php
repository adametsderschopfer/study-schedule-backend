<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Account;
use App\Models\ScheduleSetting;
use App\Models\Faculty;
use App\Models\Department;
use App\Models\Building;
use App\Models\BuildingClassroom;

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
            $account->groups()->delete();
            $account->subjects()->delete();
            $account->buildings()->delete();
        });

        Account::restored(function($account) {
            $account->schedule_settings()->withTrashed()->restore();
            $account->faculties()->withTrashed()->restore();
            $account->teachers()->withTrashed()->restore();
            $account->groups()->withTrashed()->restore();
            $account->subjects()->withTrashed()->restore();
            $account->buildings()->withTrashed()->restore();
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

        Building::deleted(function ($building) {
            foreach ($building->building_classrooms as $building_classroom) {
                foreach ($building_classroom->schedules as $schedule) {
                    $schedule->building_classroom_id = null;
                    $schedule->save();
                }
            }
            $building->building_classrooms()->delete();
        });

        BuildingClassroom::deleting(function ($buildingClassroom) {
            foreach ($buildingClassroom->schedules as $schedule) {
                $schedule->building_classroom_id = null;
                $schedule->save();
            }
        });
    }
}
