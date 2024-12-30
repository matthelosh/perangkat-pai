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
    // Sinkron RaporSD
    Route::prefix('rapor')->group(function () {
        // Siswa 
        Route::prefix('siswa')->group(function () {
            Route::get('/', [RaporController::class, 'getSiswa'])->name('rapor.siswa.get');
        });
        Route::prefix('rombel')->group(function () {
            Route::get('/', [RaporController::class, 'getRombel'])->name('rapor.rombel.get');
            Route::post('/sync', [RaporController::class, 'syncRombel'])->name('rapor.rombel.sync');
        });
    });



    Route::prefix("dashboard")->group(function () {
        Route::get("/", [DashboardController::class, 'home'])->name("home");
        // Route::inertia("/", "Dashboard/Home")->name("home");
    });

    Route::prefix('tapel')->group(function () {
        Route::put('/{id}/toggle', [TapelController::class, 'toggle'])->name('tapel.toggle');
    });
    Route::prefix('semester')->group(function () {
        Route::put('/{id}/toggle', [SemesterController::class, 'toggle'])->name('semester.toggle');
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

    // ASesmen
    Route::prefix('asesmen')->group(
        function () {
            Route::get('/', [AsesmenController::class, 'index'])->name('asesmen.index');
            Route::post('/store', [AsesmenController::class, 'store'])->name('asesmen.store');
            Route::delete('/{id}', [AsesmenController::class, 'destroy'])->name('asesmen.destroy');
        }
    );

    // Rombel
    Route::resource("rombel", RombelController::class);
    Route::post("/rombel/siswa/detach/{id}/{siswa}", [
        RombelController::class,
        "detachSiswa",
    ])->name("rombel.detach.siswa");
    Route::post('/rombel/prev', [RombelController::class, 'prevRombel'])->name('rombel.prev');

    // Siswa
    Route::resource("siswa", SiswaController::class);
    Route::post("/siswa/impor", [SiswaController::class, "impor"])->name(
        "siswa.impor"
    );

    // Rencana
    Route::prefix("rencana")->group(function () {

        Route::get("/", [PerangkatControler::class, 'rencana'])->name('rencana');
        // Kurikulum
        Route::prefix('kurikulum')->group(function () {
            Route::get("/", [KurikulumController::class, 'index'])->name('kurikulum.index');
            Route::resource("cp", CpController::class);
            Route::resource("elemen", ElemenController::class);
            Route::resource("materi", MateriAjarController::class);
            Route::resource('submateri', SubMateriController::class);
            Route::post('/submateri/impor', [SubMateriController::class, 'impor'])->name('kurikulum.submateri.impor');
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
        Route::resource("modulajar", ModulAjarController::class);
    });

    // Pelaksanaan
    Route::prefix('pelaksanaan')->group(function () {
        Route::get('/', [PerangkatControler::class, 'pelaksanaan'])->name('pelaksanaan');
        Route::get('/jurnal', [PerangkatControler::class, 'jurnal'])->name('perangkat.jurnal');
        Route::get('/modul-ajar', [PerangkatControler::class, 'modulajar'])->name('perangkat.modulajar');
        Route::get('/presensi', [PerangkatControler::class, 'presensi'])->name('perangkat.presensi');
    });

    // Evaluasi
    Route::prefix('evaluasi')->group(function () {
        Route::get("/", [PerangkatControler::class, 'evaluasi'])->name('evaluasi');
        Route::get("/formatif", [PerangkatControler::class, 'nilaiFormatif'])->name('perangkat.evaluasi.formatif');
        Route::get("/pts", [PerangkatControler::class, 'nilaiPts'])->name('perangkat.evaluasi.sumatif.pts');
        Route::prefix('pas')->group(
            function () {
                Route::get("/", [PerangkatControler::class, 'nilaiPas'])->name('perangkat.evaluasi.sumatif.pas');
                Route::prefix("nilai")->group(function () {
                    Route::post("/", [NilaiController::class, 'store'])->name('perangkat.evaluasi.nilai.store');
                });
            }
        );

        Route::prefix('soal')->group(
            function () {
                Route::get('/', [SoalController::class, 'index'])->name('soal.index');
            }
        );
    });

    // Settings
    Route::prefix('setting')->middleware(['role:admin'])->group(function () {
        Route::resource("user", UserController::class)->name(
            "index",
            "setting.account"
        );
        Route::resource("/menu", MenuController::class);
    });

    // KKG
    Route::prefix("kkg")->group(
        function () {
            Route::prefix("kegiatan")->group(
                function () {
                    Route::get("/", [KkgController::class, 'kegiatan'])->name('kkg.kegiatan');
                }
            );
        }
    );

    // Logout
    Route::post("/logout", function () {
        Auth::logout();
    })->name("logout");
});
