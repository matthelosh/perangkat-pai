<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Jadwal;
use App\Models\Rombel;
use Illuminate\Http\Request;

class RencanaController extends Controller
{
    public function index(Request $request) {
        $rombels = Rombel::where('guru_id', auth()->user()->userable->nip)->get();
        $jadwals = Jadwal::where('guru_id', auth()->user()->userable->nip)->with('rombel')->get();
        return Inertia::render("Dashboard/Perangkat/Rencana/index", [
            'rombels' => $rombels,
            'jadwals' => $jadwals
        ]);
    }
}
