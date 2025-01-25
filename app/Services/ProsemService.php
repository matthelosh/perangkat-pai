<?php

namespace App\Services;

use App\Models\Atp;
use App\Models\Jadwal;
use App\Models\Prota;
use App\Models\Tapel;
use App\Models\Kaldik;
use App\Models\Prosem;
use App\Models\Rombel;
use App\Models\Semester;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class ProsemService
{
    public function index($tingkat = null, $mine = '1')
    {
        $rombel = Rombel::whereGuruId(Auth::user()->userable->nip)->whereTingkat($tingkat)->whereTapel(\tapel()->kode)->with('jadwals')->first();
        if ($mine == 'true') {
            $atps = Atp::where('guru_id', auth()->user()->userable->nip)
                ->whereTingkat($tingkat)
                ->whereSemester($this->semester()->kode)
                ->with('prosems')
                ->get();

            $protas = Prota::
                // whereGuruId(auth()->user()->userable->nip)
                whereRombelId($rombel->kode)
                ->whereSemester($this->semester()->kode)
                ->with('atp')
                ->get();
        } else {
            $atps = Atp::whereNull('guru_id')
                ->whereTingkat($tingkat)
                ->whereSemester($this->semester()->kode)
                ->with('prosems')
                ->get();

            $protas = Prota::whereNull('guru_id')
                ->whereNull('rombel_id')
                ->whereSemester($this->semester()->kode)
                ->with('atp')
                ->get();
        }

        return [
            'atps' => $atps,
            'rombel' => $rombel,
            'kaldiks' => $this->kaldiks($this->tapel()),
            'protas' => $protas,
        ];
    }


    private function rombel($kode)
    {
        return Rombel::whereKode($kode)->with('jadwals')->first();
    }
    private function semester()
    {
        return Semester::whereStatus('active')->first();
    }
    private function tapel()
    {
        return Tapel::whereStatus('active')->first();
    }

    private function kaldiks($tapel)
    {
        $tahuns = explode("/", $this->tapel()->label);
        $start = Carbon::createFromDate($tahuns[0], 7, 1, 'Asia/Jakarta')->startOfDay();
        $end = Carbon::createFromDate($tahuns[1], 12, 31, 'Asia/Jakarta')->endOfDay();
        $kaldiks = Kaldik::whereBetween('mulai', [$start, $end])->where('tapel_id', $tapel->kode)->get();

        return $kaldiks;
    }
}
