<?php

namespace App\Http\Controllers;

use App\Models\Atp;
use Inertia\Inertia;
use App\Models\Prota;
use App\Models\Tapel;
use App\Models\Kaldik;
use App\Models\Rombel;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class ProtaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            // if ($)
            $rombel = Rombel::where('kode', $request->query('rombel'))->with('jadwals')->first();
            
            if ($request->query('mine')) {
                $nip = auth()->user()->userable->nip;
                $atps = Atp::where('guru_id', $nip)    
                            ->where('tingkat', $rombel->tingkat)
                            ->with('elemen')
                            ->with('protas', function($q) use($nip) {
                                $q->where('guru_id', $nip);
                            })
                            ->get();
            } else {
                $atps = Atp::where('tingkat', $rombel->tingkat)
                            ->whereNull('guru_id')
                            ->with('elemen')
                            ->with('protas', function($q) {
                                $q->whereNull('guru_id');
                            })
                            ->get();
            }
            // dd($atps[0]->protas);
            return Inertia::render("Dashboard/Perangkat/Rencana/Prota", [
                // 'protas' => $protas,
                'agendas' => $this->kaldiks(),
                'atps' => $atps,
                'rombel' => $rombel,
            ]);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function kaldiks()
    {
        $tapel = Tapel::whereStatus('active')->first();
        $tahuns = explode("/", $tapel->label);
        $start = Carbon::createFromDate($tahuns[0], 7, 1, 'Asia/Jakarta')->startOfDay();
        $end = Carbon::createFromDate($tahuns[1], 12, 31, 'Asia/Jakarta')->endOfDay();
        return Kaldik::whereBetween('mulai', [$start, $end])->where('tapel_id', $tapel->kode)->get();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $data = $request->data;
            $prota = Prota::updateOrCreate(
                [
                    'id' => $data['id'] ?? null,
                ],
                [
                    'guru_id' => $data['guru_id'],
                    'rombel_id' => $data['rombel_id'],
                    'atp_id' => $data['atp_id'],
                    'tanggal' => $data['tanggal'],
                    'semester' => $data['semester'],
                    'minggu_ke' => $data['minggu_ke'],
                    'aw' => $data['aw']
                ]
            );

            return back()->with('status', 'Prota disimpan');
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Prota $prota)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Prota $prota)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Prota $prota)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Prota $prota)
    {
        //
    }
}
