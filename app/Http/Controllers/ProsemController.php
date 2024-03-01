<?php

namespace App\Http\Controllers;

use App\Models\Atp;
use Inertia\Inertia;
use App\Models\Tapel;
use App\Models\Jadwal;
use App\Models\Kaldik;
use App\Models\Prosem;
use App\Models\Rombel;
use App\Models\Semester;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class ProsemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $rombel = Rombel::whereKode($request->query('rombel'))->with('jadwals')->first();
            $semester = Semester::whereStatus('active')->first();
            $tapel = Tapel::whereStatus('active')->first();
            $tahuns = explode("/", $tapel->label);
            $start = Carbon::createFromDate($tahuns[0], 7, 1, 'Asia/Jakarta')->startOfDay();
            $end = Carbon::createFromDate($tahuns[1], 12, 31, 'Asia/Jakarta')->endOfDay();
            $kaldiks = Kaldik::whereBetween('mulai', [$start, $end])->where('tapel_id', $tapel->kode)->get();
            if ($request->query('mine') == '1') {
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
            // dd($atps);
            return Inertia::render('Dashboard/Perangkat/Rencana/Prosem', [
                'atps' => $atps,
                'rombel' => $rombel,
                'kaldiks' => $kaldiks
            ]);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            // dd($request->all());
            $data = $request->atp;
            // dd($data);
            $jadwal = Jadwal::whereRombelId($data['rombel_id'])->first();
            $i=0;
            $deleteNotInTanggal = Prosem::whereAtpId($data['id'])->whereNotIn('tanggal', $data['prosems']['tanggals'])->delete();
            // dd($deleteNotInTanggal);
            foreach ($data['prosems']['tanggals'] as $tanggal) {
                $date = Carbon::parse($data['prosems']['tanggals'][$i]);
                
                Prosem::updateOrCreate(
                    [
                        'id' => $data['prosems']['ids'][$i] ?? null
                    ],
                    [
                        'guru_id' => $data['guru_id'],
                        'atp_id' =>  $data['id'],
                        'rombel_id' => $data['rombel_id'],
                        'tingkat' => $data['tingkat'],
                        'tanggal'  => $tanggal,
                        'minggu_ke' => $date->weekNumberInMonth,
                        'hari' => $jadwal->hari,
                    ]
                );

                $i++;
            }

            return back()->with('message', 'Data Prosem disimpan');
        } catch (\Throwable $th) {
            throw $th;
            // return back()->withErrors(['message' => $e->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Prosem $prosem)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Prosem $prosem)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Prosem $prosem)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, $atp_id)
    {
        try {
            Prosem::where('atp_id', $atp_id)->delete();
            return back()->with('message', 'Prosem dihapus');
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
