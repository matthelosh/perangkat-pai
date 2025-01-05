<?php

namespace App\Http\Controllers;

use App\Models\Tp;
use Inertia\Inertia;
use App\Models\Elemen;
use App\Models\MateriAjar;
use Illuminate\Http\Request;

class TpController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            if ($request->query('fase')) {
                $tingkat = $request->fase == 'A' ? ['1', '2'] : ($request->fase == 'B' ? ['3', '4'] : ['5', '6']);
                $materis = MateriAjar::whereIn('tingkat', $tingkat)->get();
                if ($request->query('mine') == 'true') {
                    // dd($request->query('mine'));
                    $nip = auth()->user()->userable->nip;
                    $elemens  = Elemen::where('fase', $request->query('fase'))->with('tps', function ($q) use ($nip) {
                        $q->where('guru_id', $nip);
                    })->get();
                } else {
                    $elemens = Elemen::where('fase', $request->query('fase'))->with('tps', function ($q) {
                        $q->where('guru_id', null);
                    })->get();
                }
            }

            return Inertia::render('Dashboard/Perangkat/Rencana/Tp', ['elemens' => $elemens, 'materis' => $materis]);
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
            $elemen = $request->elemen;
            $i = 0;
            foreach ($elemen['tps'] as $tp) {
                // $kode = $request->query('mine') == '1' ? auth()->user()->userable->nip . '-' . substr($elemen['kode'], 0, 2) . '-' . $i + 1 : substr($elemen['kode'], 0, 2) . '-' . $i + 1;
                $latestKode = Tp::where('elemen_id', $elemen['kode'])->orderBy('id', 'DESC')->pluck('kode')->first();
                // $latest = !$last ? [0, 0] : explode("-", $last->kode);
                $urut = 0;
                if ($latestKode !== null) {
                    $plitKode = \explode("-", $latestKode);
                    $urut = (int) $plitKode[1];
                }
                $kode = \str_replace("-", "", $elemen['kode']) . '-' . ($urut + 1);

                $tp = Tp::updateOrCreate(
                    [
                        'id' => $tp['id'] ?? null,
                        'kode' => $kode,
                        'elemen_id' => $elemen['kode'],
                        'fase' => $elemen['fase'],
                        'guru_id' => $request->query('mine') == '1' ? auth()->user()->userable->nip : null
                    ],
                    [
                        'kompetensi' => $tp['kompetensi'],
                        'materi' => $tp['materi'],
                        'teks' => $tp['teks']
                    ]
                );
                $last = $tp;

                $i++;
            }

            return back()->with('message', 'Tujuan Pembelajaran disimpan');
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    /**
     * Get Tp for Select Element
     */
    public function show(Tp $tp) {}
    /**
     * 
     * Get Tp for Select Element
     */
    public function list(Request $request)
    {
        try {
            $elemen = $request->elemen;
            $fase = (int) $request->tingkat > 4 ? 'C' : ((int) $request->tingkat > 2 ? 'B' : 'A');

            $tps = Tp::where('fase', $fase)
                ->where('elemen_id', $elemen)
                ->get(['kode', 'teks']);
            return response()->json(['tps' => $tps], 200);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Tp $tp)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Tp $tp)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, $id)
    {
        try {
            $tp = Tp::findOrFail($id);
            $tp->delete();
            return back()->with('message', 'TP dihapus');
        } catch (\Exception $e) {
            return back()->withErrors('message', $e->getMessage());
        }
    }
}
