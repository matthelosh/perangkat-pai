<?php

namespace App\Services;

use App\Models\Atp;
use App\Models\Tapel;
use App\Models\Kaldik;
use App\Models\Rombel;
use App\Models\Semester;
use Illuminate\Support\Carbon;

class ProsemService {
    public function index($rombel=null, $mine='1')  {
        $rombel = Rombel::whereKode($rombel)->with('jadwals')->first();
        $semester = Semester::whereStatus('active')->first();
        $tapel = Tapel::whereStatus('active')->first();
        $tahuns = explode("/", $tapel->label);
        $start = Carbon::createFromDate($tahuns[0], 7, 1, 'Asia/Jakarta')->startOfDay();
        $end = Carbon::createFromDate($tahuns[1], 12, 31, 'Asia/Jakarta')->endOfDay();
        $kaldiks = Kaldik::whereBetween('mulai', [$start, $end])->where('tapel_id', $tapel->kode)->get();
        if ($mine == '1') {
            $atps = Atp::where('guru_id', auth()->user()->userable->nip)
                        ->whereTingkat($rombel->tingkat)
                        ->whereSemester($semester->kode)
                        ->with('prosems')
                        ->get();
        } else {
            $atps = Atp::whereNull('guru_id')
                    ->whereTingkat($rombel->tingkat)
                    ->whereSemester($semester->kode)
                    ->with('prosems')
                    ->get();
        }

        return [
            'atps' => $atps,
            'rombel' => $rombel,
            'kaldiks' => $kaldiks
        ];
    }
}