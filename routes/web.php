<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AccountController;

Route::get('/', [HomeController::class, 'index'])->name("home");


Route::group(['prefix' => 'account'], function () {
    Route::group(['middleware' => 'guest'], function () {
        Route::get('/register', [AccountController::class, 'registration'])->name("registration");
        Route::post('/submit-register', [AccountController::class, 'submitRegistration'])->name("submitRegistration");
        Route::get('/login', [AccountController::class, 'login'])->name("login");
        Route::post('/authenticate', [AccountController::class, 'authenticate'])->name("authenticate");
    });

    Route::group(['middleware' => 'auth'], function () {
        Route::get('/profile', [AccountController::class, 'profile'])->name("profile");
        Route::get('/logout', [AccountController::class, 'logout'])->name("logout");
        Route::put('/update-profile', [AccountController::class, 'updateProfile'])->name("updateProfile");
        Route::post('/update-profile-pic', [AccountController::class, 'updateProfilePic'])->name("updateProfilePic");
        Route::get('/create-job', [AccountController::class, 'createJob'])->name("createJob");
        Route::post('/save-job', [AccountController::class, 'saveJob'])->name("saveJob");
        Route::get('/my-jobs', [AccountController::class, 'myJobs'])->name("myJobs");
    });
});
