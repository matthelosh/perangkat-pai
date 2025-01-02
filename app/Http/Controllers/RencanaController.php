<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Jadwal;
use App\Models\Kaldik;
use App\Models\Rombel;
use App\Models\Tapel;
use Illuminate\Http\Request;

class RencanaController extends Controller
{
    public function index(Request $request)
    {
        $rombels = Rombel::where('guru_id', auth()->user()->userable->nip)->get();
        // dd($rombels);
        $jadwals = Jadwal::where('guru_id', auth()->user()->userable->nip)->with('rombel')->get();
        return Inertia::render("Dashboard/Perangkat/Rencana/index", [
            'rombels' => $rombels,
            'jadwals' => $jadwals
        ]);
    }

    public function ape(Request $request)
    {
        $rombel = Rombel::where([
            ['tingkat', '=', $request->tingkat],
            ['guru_id', '=', $request->user()->userable->nip],
            ['tapel', '=', \tapel()->kode]
        ])->with('jadwals')->first();
        $kaldiks = Kaldik::whereIsLibur(true)->where('tapel_id', $this->tapel())->get();
        //    dd($request->all());
        return Inertia::render("Dashboard/Perangkat/Rencana/Ape", [
            'kaldiks' => $kaldiks,
            'rombel' => $rombel
        ]);
    }

    private function tapel()
    {
        return Tapel::whereStatus('active')->pluck('kode')->first();
    }
}
