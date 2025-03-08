<?php

namespace App\Http\Controllers;

use App\Models\Guru;
use App\Models\Kaldik;
use Illuminate\Http\Request;
use Inertia\Inertia;

class KkgController extends Controller
{


    // Kegiatan
    public function kegiatan(Request $request)
    {
        try {
            $kegiatans = Kaldik::wherePelaksana("kkg")->with('kuitansis')->get();
            return Inertia::render('Dashboard/Kkg/Kegiatan', [
                'kegiatans' => $kegiatans,
                'gurus' => Guru::with('sekolah')->get()
            ]);
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
