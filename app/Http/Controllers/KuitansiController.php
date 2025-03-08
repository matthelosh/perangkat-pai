<?php

namespace App\Http\Controllers;

use App\Models\Kuitansi;
use App\Models\Sekolah;
use Carbon\Carbon;
use Illuminate\Http\Request;

class KuitansiController extends Controller
{
    public function store(Request $request)
    {
        try {
            $sekolahs = Sekolah::all();
            $no = (int) $request->no;
            foreach ($sekolahs as $sekolah) {
                $store = Kuitansi::updateOrCreate(
                    [
                        'id' => $request->id ?? null,
                    ],
                    [
                        'no' => $no . '-' . $sekolah->npsn,
                        'tanggal' => Carbon::parse($request->tanggal)->setTimezone('Asia/Jakarta')->format('Y-m-d'),
                        'dari' => $sekolah->nama,
                        'terbilang' => $request->terbilang,
                        'kegiatan_id' => $request->kegiatan_id,
                        'jumlah' => $request->jumlah,
                    ]
                );
                $no++;
            }

            return back()->with('message', 'Kuitansi Kegiatan Disimpan');
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
