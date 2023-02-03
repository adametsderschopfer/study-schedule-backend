<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\v1\Admin\AccountController;
use App\Http\Controllers\API\v1\Admin\ModeController;

Route::prefix('v1')->group(function()
{
    Route::middleware('hasAccount')
        ->prefix('admin')
        ->group(function()
    {
        Route::resource('accounts', AccountController::class);
        Route::resource('modes', ModeController::class);
    });
});
