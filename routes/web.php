<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\admin\DashboardController;
use App\Http\Controllers\admin\UserController;
use App\Http\Controllers\JobsController;

Route::get('/', [HomeController::class, 'index'])->name("home");
Route::get('/jobs', [JobsController::class, 'index'])->name("jobs");
Route::get('/jobs/detail/{jobId}', [JobsController::class, 'jobDetail'])->name("jobDetail");
Route::post('/apply-job', [JobsController::class, 'applyJob'])->name("applyJob");
Route::post('/save-detail-job', [JobsController::class, 'saveDetailJob'])->name("saveDetailJob");

Route::group(['prefix' => 'admin', 'middleware' => 'checkRole'], function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name("dashboard");
    Route::get('/users', [UserController::class, 'index'])->name("admin.users");
    Route::get('/users/{id}', [UserController::class, 'edit'])->name("admin.edit");
    Route::put('/users/{id}', [UserController::class, 'update'])->name("admin.update");
    Route::delete('/users', [UserController::class, 'destroy'])->name("admin.destroy");
});

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
        Route::get('/edit/{jobId}', [AccountController::class, 'editJob'])->name("editJob");
        Route::post('/update-job/{jobId}', [AccountController::class, 'updateJob'])->name("updateJob");
        Route::post('/delete-job', [AccountController::class, 'deleteJob'])->name("deleteJob");
        Route::get('/my-job-applications', [AccountController::class, 'myJobApplications'])->name("myJobApplications");
        Route::post('/remove-job-application', [AccountController::class, 'removeJob'])->name("removeJob");
        Route::get('/saved-jobs', [AccountController::class, 'savedJobs'])->name("savedJobs");
        Route::post('/remove-saved-job', [AccountController::class, 'removeSavedJobs'])->name("removeSavedJobs");
        Route::post('/update-password', [AccountController::class, 'updatePassword'])->name("updatePassword");
    });
});
