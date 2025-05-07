<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\ProfileController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\NutritionalPlanController;
use App\Http\Controllers\TrainingController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function () {

    Route::delete('/logout', [LoginController::class, 'destroy'])->name('logout');
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile');

    Route::apiResource('trainings', TrainingController::class);
    Route::apiResource('nutritional-plans', NutritionalPlanController::class);
});

Route::post('/login', [LoginController::class, 'store'])->name('signin');
Route::post('/register', [RegisterController::class, 'store'])->name('register');
