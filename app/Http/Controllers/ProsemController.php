<?php

namespace App\Http\Controllers;

use App\Models\Atp;
use Inertia\Inertia;
use App\Models\Tapel;
use App\Models\Kaldik;
use App\Models\Prosem;
use App\Models\Rombel;
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
            $semester = $request->query('semester') ?? '1';
            $tapel = Tapel::whereStatus('active')->first();
            $tahuns = explode("/", $tapel->label);
            $start = Carbon::createFromDate($tahuns[0], 7, 1, 'Asia/Jakarta')->startOfDay();
            $end = Carbon::createFromDate($tahuns[1], 12, 31, 'Asia/Jakarta')->endOfDay();
            $kaldiks = Kaldik::whereBetween('mulai', [$start, $end])->where('tapel_id', $tapel->kode)->get();
            if ($request->query('mine') == '1') {
                $atps = Atp::where('guru_id', auth()->user()->userable->nip)
                            ->whereTingkat($rombel->tingkat)
                            ->whereSemester($semester)
                            ->get();
            } else {
                $atps = Atp::whereNull('guru_id')
                            ->whereTingkat($rombel->tingkat)
                            ->whereSemester($semester)
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
        //
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
    public function destroy(Prosem $prosem)
    {
        //
    }
}
