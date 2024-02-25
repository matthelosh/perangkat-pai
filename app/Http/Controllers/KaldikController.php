<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Tapel;
use App\Models\Kaldik;
use Carbon\Carbon;
use Illuminate\Http\Request;

class KaldikController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $tapel = Tapel::whereStatus('active')->first();
        $tahuns = explode("/", $tapel->label);
        $start = Carbon::createFromDate($tahuns[0], 7, 1, 'Asia/Jakarta')->startOfDay();
        $end = Carbon::createFromDate($tahuns[1], 12, 31, 'Asia/Jakarta')->endOfDay();
        $agendas = Kaldik::whereBetween('mulai', [$start, $end])->where('tapel_id', $tapel->kode)->get();

        return Inertia::render("Dashboard/Utama/Kaldik", ['agendas' => $agendas]);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $data = $request->data;
            // dd($data['is_libur']);
            Kaldik::updateOrCreate(
                [
                    'id' => $data['id'] ?? null,
                ],
                [
                    "tapel_id" => $data['tapel_id'],
                    "label" => $data['label'],
                    "mulai" => $data['mulai'],
                    "selesai" => $data['selesai'],
                    "deskripsi" => $data['deskripsi'],
                    "is_libur" => $data['is_libur'],
                    "warna" => $data['is_libur'] ? 'red': 'blue',
                ]
            );
            return back()->with('message','Kaldik disimpan');
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Kaldik $kaldik)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Kaldik $kaldik)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Kaldik $kaldik)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Kaldik $kaldik)
    {
        //
    }
}
