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
    Route::resource("sekolah", SekolahController::class)->only(['index','store','import','update','destroy']);
    Route::post("/sekolah/impor", [SekolahController::class, 'import'])->name('sekolah.import');

    // Guru
    Route::resource("guru", GuruController::class)->only(['index','store','import','update','destroy']);
    Route::post("/guru/impor", [GuruController::class, 'import'])->name('guru.import');

    // Rombel
    Route::resource("rombel", RombelController::class);

// Settings
    Route::prefix("setting")->group(function() {
        Route::resource("user", UserController::class)->name('index', 'setting.account');
        Route::resource("/menu", MenuController::class);
    });



// Logout
    Route::post("/logout", function() {
        Auth::logout();
    })->name('logout');
});