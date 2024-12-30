<?php

namespace App\Http\Controllers;

use App\Models\Atp;
use Inertia\Inertia;
use App\Models\Rombel;
use App\Models\ModulAjar;
use Illuminate\Http\Request;

class ModulAjarController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $nip = $request->user()->userable->nip;
            $rombel = Rombel::where('tingkat', $request->query('tingkat'))->where('guru_id', $nip)->with('jadwals')->first();
            if ($request->query('mine')) {
                $nip = auth()->user()->userable->nip;
                $atps = Atp::where('guru_id', $nip)
                    ->where('tingkat', $request->tingkat)
                    ->with('elemen')
                    ->with('protas', function ($q) use ($nip) {
                        $q->where('guru_id', $nip);
                    })
                    ->with('mas')
                    ->get();
            } else {
                $atps = Atp::where('tingkat', $request->tingkat)
                    ->whereNull('guru_id')
                    ->with('elemen')
                    ->with('protas', function ($q) {
                        $q->whereNull('guru_id');
                    })
                    ->get();
            }
            return Inertia::render("Dashboard/Perangkat/Rencana/Modulajar", [
                'atps' => $atps,
                'rombel' => $rombel
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
            $data = $request->data;
            $store = ModulAjar::updateOrCreate(
                [
                    'id' => $data['id'] ?? null,
                ],
                [
                    'atp_id' => $data['atp_id'],
                    'p5' => $data['p5'],
                    'tps' => $data['tps'],
                    'media' => $data['media'],
                    'pemahaman' => $data['pemahaman'],
                    'pertanyaan' => $data['pertanyaan'],
                    'pendahuluan' => $data['pendahuluan'],
                    'inti' => $data['inti'],
                    'penutup' => $data['penutup'],
                    'asesmen' => $data['asesmen'],
                    'referensi' => $data['referensi']

                ]
            );
            return back()->with('message', 'Modul Ajar Disimpan');
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(ModulAjar $modulAjar)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ModulAjar $modulAjar)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ModulAjar $modulAjar)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ModulAjar $modulAjar, $id)
    {
        try {
            $modulAjar::destroy($id);
            return \back()->with('message', 'Modul Ajar dihapus');
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
