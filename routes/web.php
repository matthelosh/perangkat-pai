<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::inertia("/", "Welcome")->name("welcome");
Route::prefix("masuk")->group(function () {
    Route::inertia("/", "Masuk")->name("login");
    Route::post("/", [AuthController::class, "authenticate"])->name(
        "auth.login"
    );
});

Route::middleware("auth")->group(function () {
    Route::prefix("dashboard")->group(function () {
        Route::inertia("/", "Dashboard/Home")->name("home");
    });

    Route::inertia("/about", "Dashboard/About")->name("about");

    // Sekolah
    Route::resource("sekolah", SekolahController::class)->only([
        "index",
        "store",
        "import",
        "update",
        "destroy",
    ]);
    Route::post("/sekolah/impor", [SekolahController::class, "import"])->name(
        "sekolah.import"
    );

    // Guru
    Route::resource("guru", GuruController::class)->only([
        "index",
        "store",
        "import",
        "update",
        "destroy",
    ]);
    Route::post("/guru/impor", [GuruController::class, "import"])->name(
        "guru.import"
    );

    // Rombel
    Route::resource("rombel", RombelController::class);
    Route::post("/rombel/siswa/detach/{id}/{siswa}", [
        RombelController::class,
        "detachSiswa",
    ])->name("rombel.detach.siswa");

    // Siswa
    Route::resource("siswa", SiswaController::class);
    Route::post("/siswa/impor", [SiswaController::class, "impor"])->name(
        "siswa.impor"
    );
    // Rencana
    Route::prefix("rencana")->group(function() {

        Route::get("/", [PerangkatControler::class, 'rencana'])->name('rencana');
        // Kurikulum
        Route::prefix('kurikulum')->group(function() {
            Route::get("/", [KurikulumController::class, 'index'])->name('kurikulum.index');
            Route::resource("cp", CpController::class);
        });
        
        // Tujuan Pembelajaran
        Route::resource('tp', TpController::class);
        
        // alur Tujuan Pembelajaran
        Route::resource("atp", AtpController::class);
        Route::post('/atp/destroy/all', [AtpController::class, 'destroyAll'])->name('atp.destroy.all');

        // P
        // Kaldik
        Route::resource("kaldik", KaldikController::class);

        // Jadwal
        Route::resource('jadwal', JadwalController::class);

        // APE
        Route::get("pekan-efektif", [RencanaController::class, 'ape'])->name('ape.index');

        Route::resource("prota", ProtaController::class);
        Route::get("/prosem/cetak", [ProsemController::class, 'cetak'])->name('prosem.cetak');
        Route::resource("prosem", ProsemController::class);
        
    });

    // Pelaksanaan
    Route::prefix('pelaksanaan')->group(function() {
        Route::get('/', [PerangkatControler::class, 'pelaksanaan'])->name('pelaksanaan');
    });

    // Settings
    Route::prefix("setting")->group(function () {
        Route::resource("user", UserController::class)->name(
            "index",
            "setting.account"
        );
        Route::resource("/menu", MenuController::class);
    });

    // Logout
    Route::post("/logout", function () {
        Auth::logout();
    })->name("logout");
});
