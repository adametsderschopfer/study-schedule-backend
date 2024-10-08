<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\v1\Admin\AccountController;
use App\Http\Controllers\API\v1\Admin\ScheduleSettingController;
use App\Http\Controllers\API\v1\Admin\FacultyController;
use App\Http\Controllers\API\v1\Admin\DepartmentController;
use App\Http\Controllers\API\v1\Admin\GroupController;
use App\Http\Controllers\API\v1\Admin\TeacherController;
use App\Http\Controllers\API\v1\Admin\ScheduleController;
use App\Http\Controllers\API\v1\Admin\SubjectController;
use App\Http\Controllers\API\v1\Admin\BuildingController;
use App\Http\Controllers\API\v1\Client\AccountClientController;
use App\Http\Controllers\API\v1\Client\FacultyClientController;
use App\Http\Controllers\API\v1\Client\ScheduleClientController;
use App\Http\Controllers\API\v1\Client\TeacherClientController;
use App\Http\Controllers\API\v1\Client\BuildingClientController;

Route::prefix('v1')->group(function()
{
    Route::middleware('clientHasAccount')
        ->prefix('client')
        ->group(function()
    {
        Route::get('me', [AccountClientController::class, 'index']);
        Route::get('faculties', [FacultyClientController::class, 'index']);
        Route::get('schedules', [ScheduleClientController::class, 'index']);
        Route::get('teachers', [TeacherClientController::class, 'index']);
        Route::get('buildings', [BuildingClientController::class, 'index']);
    });

    Route::middleware('hasAccount')
        ->prefix('admin')
        ->group(function()
    {
        Route::get('me', [AccountController::class, 'index']);
        Route::resource('schedule_settings', ScheduleSettingController::class);
        Route::resource('faculties', FacultyController::class);
        Route::resource('departments', DepartmentController::class);
        Route::resource('teachers', TeacherController::class);
        Route::get('teacher/search', [TeacherController::class, 'search']);
        Route::resource('schedules', ScheduleController::class);
        Route::resource('subjects', SubjectController::class);
        Route::get('subject/search', [SubjectController::class, 'search']);
        Route::resource('groups', GroupController::class);
        Route::resource('buildings', BuildingController::class);
        Route::get('schedules_export',[ScheduleController::class, 'export']);
    });
});
