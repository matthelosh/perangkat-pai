<?php

namespace App\Http\Controllers;

use App\Models\Asesmen;
use Inertia\Inertia;
use App\Models\Prota;
use App\Models\Tapel;
use App\Models\Jadwal;
use App\Models\Kaldik;
use App\Models\Rombel;
use App\Models\Semester;
use Illuminate\Http\Request;

class PerangkatControler extends Controller
{
    // Rencana
    public function rencana(Request $request)
    {
        return Inertia::render("Dashboard/Perangkat/Rencana/index", [
            'rombels' => $this->rombelku($request->user()),
            'jadwals' => $this->jadwalku($request->user())
        ]);
    }

    public function ape(Request $request)
    {
        //    dd($request->all());
        return Inertia::render("Dashboard/Perangkat/Rencana/Ape", [
            'kaldiks' => $this->kaldiks(),
            'rombel' => $this->rombel($request->query('rombel'))
        ]);
    }

    // Pelaksanaan
    public function pelaksanaan(Request $request)
    {
        try {
            return Inertia::render("Dashboard/Perangkat/Pelaksanaan/index", [
                'rombels' => $this->rombelku($request->user()),
                'jadwals' => $this->jadwalku($request->user()),
            ]);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    // Jurnal
    public function jurnal(Request $request)
    {
        return Inertia::render("Dashboard/Perangkat/Pelaksanaan/Jurnal", [
            'protas' => Prota::whereGuruId(auth()->user()->userable->nip)
                ->whereRombelId($request->query('rombel'))
                ->whereSemester($this->semester()->kode)
                ->whereHas('atp')
                ->with('atp.elemen')
                ->get(),
            'rombel' => Rombel::whereKode($request->query('rombel'))->with('siswas')->first(),
        ]);
    }
    public function modulajar(Request $request)
    {
        return Inertia::render("Dashboard/Perangkat/Pelaksanaan/Ma", [
            'protas' => Prota::whereGuruId(auth()->user()->userable->nip)
                ->whereRombelId($request->query('rombel'))
                ->whereSemester($this->semester()->kode)
                ->whereHas('atp')
                ->with('atp.elemen')
                ->get(),
            'rombel' => Rombel::whereKode($request->query('rombel'))->with('siswas')->first(),
        ]);
    }
    public function presensi(Request $request)
    {

        return Inertia::render("Dashboard/Perangkat/Pelaksanaan/Presensi", [
            'rombel' => Rombel::whereKode($request->query('rombel'))->with('siswas', function($s) {
                $s->orderBy('nama', 'ASC');
            })->first(),
            'protas' => Prota::whereGuruId(auth()->user()->userable->nip)
                ->whereRombelId($request->query('rombel'))
                ->whereSemester($this->semester()->kode)
                ->whereHas('atp')
                ->with('atp.elemen')
                ->get(),
        ]);
    }

    // Evaluasi
    public function evaluasi(Request $request)
    {
        try {
            return Inertia::render("Dashboard/Perangkat/Evaluasi/index", [
                'rombels' => Rombel::whereGuruId(auth()->user()->userable->nip)
                    ->whereTapel($this->tapel()->kode)
                    ->get(),
                'protas' => Prota::whereGuruId(auth()->user()->userable->nip)
                    ->whereRombelId($request->query('rombel'))
                    ->whereSemester($this->semester()->kode)
                    ->whereHas('atp')
                    ->with('atp.elemen')
                    ->get(),
            ]);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function nilaiFormatif(Request $request)
    {
        $rombel = $this->rombel($request->query('rombel'));
        $rombelId = $rombel->id;
        $asesmens = Asesmen::where([
            ['tingkat', '=', $rombel->tingkat],
            ['tapel', '=', tapel()->kode],
            ['semester', '=', semester()->kode],
            ['tipe', '=', 'uh']
        ])
            ->with([
                'analises'
                => function ($a) use ($rombelId) {
                    $a->whereRombelId($rombelId);
                    $a->with('siswa');
                }
            ])->get();
        return Inertia::render(
            'Dashboard/Perangkat/Evaluasi/Formatif',
            [
                'asesmens' => $asesmens,
                'rombel' => $rombel
            ]
        );
    }
    public function nilaiPts(Request $request)
    {
        $rombel = $this->rombel($request->query('rombel'));
        $rombelId = $rombel->id;
        $asesmen = Asesmen::where(
            [
                ['tapel', '=', tapel()->kode],
                ['semester', '=', semester()->kode],
                ['tingkat', '=', $rombel->tingkat],
                ['tipe', '=', 'pts']
            ]
        )
            ->with([
                'analises'
                => function ($a) use ($rombelId) {
                    $a->whereRombelId($rombelId);
                    $a->with('siswa');
                }
            ])
            ->first();

        return Inertia::render('Dashboard/Perangkat/Evaluasi/Pts', [
            'rombel' => $rombel,
            'asesmen' => $asesmen
        ]);
    }
    public function nilaiPas(Request $request)
    {
        try {
            $rombel = $this->rombel($request->query('rombel'));
            $rombelId = $rombel->id;
            return Inertia::render('Dashboard/Perangkat/Evaluasi/Pas', [
                'rombel' => $rombel,
                'asesmen' => Asesmen::where(
                    [
                        ['tapel', '=', tapel()->kode],
                        ['semester', '=', semester()->kode],
                        ['tingkat', '=', $rombel->tingkat],
                        ['tipe', '=', 'pas']
                    ]
                )
                    ->with([
                        'analises'
                        => function ($a) use ($rombelId) {
                            $a->whereRombelId($rombelId);
                            $a->with('siswa');
                        }
                    ])
                    ->first()
            ]);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    // Misc


    private function kaldiks()
    {
        return Kaldik::whereIsLibur(true)->whereTapelId($this->tapel()->kode)->get();
    }

    private function rombel($kode,)
    {
        return Rombel::whereKode($kode)->with('jadwals', 'siswas')->first();
    }

    private function rombelku($user)
    {
        if ($user->hasRole('gpai')) {
            return Rombel::where('guru_id', auth()->user()->userable->nip)
                ->where('tapel', $this->tapel()->kode)
                ->get();
        }
    }

    private function jadwalku($user)
    {
        $jadwals = [];
        if ($user->hasRole('gpai')) {

            $jadwals = Jadwal::where('guru_id', auth()->user()->userable->nip)->whereTapelId($this->tapel()->kode)->with('rombel')->get();
        }
        return $jadwals;
    }

    private function tapel()
    {
        return Tapel::whereStatus('active')->first();
    }

    private function semester()
    {
        return Semester::whereStatus('active')->first();
    }
}
