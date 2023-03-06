<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\v1\Admin\AccountController;
use App\Http\Controllers\API\v1\Admin\ScheduleSettingController;
use App\Http\Controllers\API\v1\Admin\FacultyController;
use App\Http\Controllers\API\v1\Admin\DepartmentController;
use App\Http\Controllers\API\v1\Admin\DepartmentSubjectController;
use App\Http\Controllers\API\v1\Admin\DepartmentGroupController;
use App\Http\Controllers\API\v1\Admin\TeacherController;
use App\Http\Controllers\API\v1\Admin\ScheduleController;

Route::prefix('v1')->group(function()
{
    Route::middleware('hasAccount')
        ->prefix('client')
        ->group(function()
    {
        Route::get('schedules', [ScheduleController::class, 'getByDay']);
    });

    Route::middleware('hasAccount')
        ->prefix('admin')
        ->group(function()
    {
        Route::get('me', [AccountController::class, 'index']);
        Route::resource('schedule_settings', ScheduleSettingController::class);
        Route::resource('faculties', FacultyController::class);
        Route::resource('departments', DepartmentController::class);
        Route::resource('department_subjects', DepartmentSubjectController::class);
        Route::resource('department_groups', DepartmentGroupController::class);
        Route::resource('teachers', TeacherController::class);
        Route::resource('schedules', ScheduleController::class);
    });
});
