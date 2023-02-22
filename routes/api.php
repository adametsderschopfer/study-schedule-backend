<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\v1\Admin\AccountController;
use App\Http\Controllers\API\v1\Admin\ScheduleSettingController;
use App\Http\Controllers\API\v1\Admin\FacultyController;
use App\Http\Controllers\API\v1\Admin\DepartmentController;

Route::prefix('v1')->group(function()
{
    Route::middleware('hasAccount')
        ->prefix('admin')
        ->group(function()
    {
        Route::get('me', [AccountController::class, 'index']);
        Route::resource('schedule_settings', ScheduleSettingController::class);
        Route::resource('faculties', FacultyController::class);
        Route::resource('departments', DepartmentController::class);
    });
});
