<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\v1\Admin\SettingController;
use App\Http\Controllers\API\v1\Admin\AccountController;

Route::prefix('v1')->group(function()
{
    Route::prefix('admin')
        ->group(function()
    {
        Route::get('me', [AccountController::class, 'index'])->middleware('hasAccount');
        Route::resource('settings', SettingController::class);
    });
});
