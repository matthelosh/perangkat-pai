<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Tapel;
use App\Models\Jadwal;
use App\Models\Kaldik;
use App\Models\Rombel;
use App\Models\Semester;
use Illuminate\Http\Request;

class PerangkatControler extends Controller
{
    // Rencana
    public function rencana(Request $request) {
        return Inertia::render("Dashboard/Perangkat/Rencana/index", [
            'rombels' => $this->rombelku(),
            'jadwals' => $this->jadwalku()
        ]);
    }

    public function ape(Request $request) {
    //    dd($request->all());
       return Inertia::render("Dashboard/Perangkat/Rencana/Ape", [
        'kaldiks' => $this->kaldiks(),
        'rombel' => $this->rombel($request->query('rombel'))
       ]);
    }

    // Pelaksanaan
    public function pelaksanaan(Request $request) {
        try {
            return Inertia::render("Dashboard/Perangkat/Pelaksanaan/index", [
                'rombels' => $this->rombelku(),
                'jadwals' => $this->jadwalku()
            ]);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    // Misc

    private function kaldiks() {
        return Kaldik::whereIsLibur(true)->whereTapelId($this->tapel()->kode)->get();
    }

    private function rombel($kode) {
        return Rombel::whereKode($kode)->with('jadwals')->first();
    }

    private function rombelku() {
        return Rombel::where('guru_id', auth()->user()->userable->nip)->get();
    }

    private function jadwalku() {
        return Jadwal::where('guru_id', auth()->user()->userable->nip)->whereTapelId($this->tapel()->kode)->with('rombel')->get();
    }

    private function tapel() {
        return Tapel::whereStatus('active')->first();
    }

    private function semester() {
        return Semester::whereStatus('active')->first();
    }
}
