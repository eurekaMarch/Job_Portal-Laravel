<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AccountController;


Route::get('/contact', [HomeController::class, 'contact'])->name("contact");


Route::get('/', [HomeController::class, 'index'])->name("home");
Route::get('/account/register', [AccountController::class, 'registration'])->name("registration");
Route::post('/account/submit-register', [AccountController::class, 'submitRegistration'])->name("submitRegistration");
