<?php

namespace App\Http\Controllers;

use App\Models\Tp;
use Inertia\Inertia;
use App\Models\Elemen;
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
                if($request->query('mine') == 'true') {
                    // dd($request->query('mine'));
                    $nip = auth()->user()->userable->nip;
                    $elemens  = Elemen::where('fase', $request->query('fase'))->with('tps', function($q) use($nip) {
                        $q->where('guru_id', $nip);
                    })->get();
                } else {
                    $elemens = Elemen::where('fase', $request->query('fase'))->with('tps')->get();
                }
                
            }

            return Inertia::render('Dashboard/Perangkat/Rencana/Tp', ['elemens' => $elemens]);
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
            foreach($elemen['tps'] as $tp)
            {
                $kode = $request->query('min') == '1' ? auth()->user()->userable->nip.'-'.substr($elemen['kode'],0,2).'-'.$i+1 : substr($elemen['kode'],0,2).'-'.$i+1;

                Tp::updateOrCreate(
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

                $i++;
            }

            return back()->with('message', 'Tujuan Pembelajaran disimpan');
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Tp $tp)
    {
        //
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
