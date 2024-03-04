<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Jadwal;
use App\Models\Prosem;
use App\Services\ProsemService;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class ProsemController extends Controller
{

    public function __construct(private ProsemService $prosemService)
    {
        
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {

            $datas = $this->prosemService->index($request->rombel, $request->mine);
            $component = 'Dashboard/Perangkat/Rencana/Prosem';
            return Inertia::render($component, [
                'atps' => $datas['atps'],
                'rombel' => $datas['rombel'],
                'kaldiks' => $datas['kaldiks']
            ]);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    /**
     * Show the print page for Prosem.
     */
    public function cetak(Request $request)
    {
        try {

            $datas = $this->prosemService->index($request->rombel, $request->mine);
            $component = 'Dashboard/Perangkat/Rencana/CetakProsem';
            return Inertia::render($component, [
                'atps' => $datas['atps'],
                'rombel' => $datas['rombel'],
                'kaldiks' => $datas['kaldiks']
            ]);
        } catch (\Throwable $th) {
            throw $th;
        }
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
