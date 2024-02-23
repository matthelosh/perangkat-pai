<?php

namespace App\Http\Controllers;

use App\Models\Cp;
use App\Models\Atp;
use Inertia\Inertia;
use App\Models\Elemen;
use App\Models\MateriAjar;
use Illuminate\Http\Request;

class AtpController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            if ($request->query('fase')) {
                if ($request->query('mine') == 'true') {
                    $nip = auth()->user()->userable->nip;
                    $elemens  = Elemen::where('fase', $request->query('fase'))->with('tps', function($q) use($nip) {
                        $q->where('guru_id', $nip);
                    })->with('atps', function($q) use ($nip) {
                        $q->where('guru_id', $nip);
                    })->get();
                } else {
                    $elemens = Elemen::where('fase', $request->query('fase'))->with('tps', 'atps')->get();
                }

                $cp = Cp::where('fase', $request->query('fase'))->first();
            }

            $babs = MateriAjar::all();
            return Inertia::render('Dashboard/Perangkat/Rencana/Atp', ['elemens' => $elemens, 'babs' => $babs, 'cp' => $cp]);
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
            // dd(!$request->query('mine'));
            $data = $request->data;
            Atp::updateOrCreate(
                [
                    'id' => $data['id'] ?? null,
                ],
                [
                    'guru_id' => $request->query('mine') ? auth()->user()->userable->nip : null,
                    'kode' => $this->kode($request->query('mine'), $data['elemen_id']) ,
                    'elemen_id' => $data['elemen_id'],
                    'tingkat' => $data['tingkat'],
                    'fase' => $data['tingkat'] > 3 ? 'C' : ($data['tingkat'] > 2 ? 'B' : 'C'),
                    'semester' => $data['semester'],
                    'aw' => $data['aw'],
                    'materi' => $data['materi'],
                    'tps' => implode(";", $data['tps']),
                    'konten' => $data['konten'],
                    'asesmen' => $data['asesmen'],
                ]
            );

            return back()->with('message', 'ATP disimpan');
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    private function kode($mine, $elemen_id) {
        if ($mine) {
            $latest = Atp::where('guru_id', auth()->user()->userable->nip)->where('elemen_id', $elemen_id)->pluck('kode')->last();

        } else {
            $latest = Atp::whereNull('guru_id')->where('elemen_id', $elemen_id)->pluck('kode')->last();
        }

        if ($latest) {
            $kode = explode("-",$latest);
            $no = intval($kode[count($kode)-1]);
            
        } else {
            $no=0;
        }

        return !$mine ? $elemen_id.'-'.$no+1 : auth()->user()->userable->nip.'-'.$elemen_id.'-'.$no+1;
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Atp $atp)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Atp $atp)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Atp $atp)
    {
        //
    }
}
