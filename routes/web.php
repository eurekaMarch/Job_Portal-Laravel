<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AccountController;

Route::get('/', [HomeController::class, 'index'])->name("home");

Route::group(['account'], function () {
    Route::group(['middleware' => 'guest'], function () {
        Route::get('/account/register', [AccountController::class, 'registration'])->name("registration");
        Route::post('/account/submit-register', [AccountController::class, 'submitRegistration'])->name("submitRegistration");
        Route::get('/account/login', [AccountController::class, 'login'])->name("login");
        Route::post('/account/authenticate', [AccountController::class, 'authenticate'])->name("authenticate");
    });

    Route::group(['middleware' => 'auth'], function () {
        Route::get('/account/profile', [AccountController::class, 'profile'])->name("profile");
        Route::get('/account/logout', [AccountController::class, 'logout'])->name("logout");
        Route::put('/account/updateProfile', [AccountController::class, 'updateProfile'])->name("updateProfile");
    });
});
