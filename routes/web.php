<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;


Route::inertia("/", "Welcome")->name("welcome");
Route::prefix("masuk")->group(function() {
    Route::inertia("/", "Masuk")->name("login");
    Route::post("/", [AuthController::class, 'authenticate'])->name('auth.login');
});


Route::middleware("auth")->group(function() {
    Route::prefix("dashboard")->group(function() {
        Route::inertia("/","Dashboard/Home")->name("home");
    });

    Route::inertia("/about", "Dashboard/About")->name("about");
    
    // Sekolah
    Route::prefix("sekolah")->group(function() {
        Route::inertia("/", "Dashboard/Sekolah")->name("sekolah");
    });

// Logout
    Route::post("/logout", function() {
        Auth::logout();
    })->name('logout');
});