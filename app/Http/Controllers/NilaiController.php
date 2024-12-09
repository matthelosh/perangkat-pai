<?php

namespace App\Http\Controllers;

use App\Models\Analisis;
use App\Models\Nilai;
use Illuminate\Http\Request;

class NilaiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
            foreach ($request->datas as $data) {
                $nilai = Nilai::updateOrCreate(
                    [
                        'id' => $data['id'] ?? null,
                        'siswa_id' => $data['siswa_id'],
                        'asesmen_id' => $data['asesmen_id'],
                        'rombel_id' => $request->rombel_id
                    ],
                    [
                        'skor' => $data['skor']
                    ]
                );

                $analisis = Analisis::updateOrCreate(
                    [
                        'asesmen_id' => $data['asesmen_id'],
                        'siswa_id' => $data['siswa_id'],
                        'rombel_id' => $request->rombel_id
                    ],
                    [

                        'pgs' => $data['pgs'],
                        'isians' => $data['isians'],
                        'uraians' => $data['uraians']
                    ]
                );
            };

            return back()->with('message', 'Nilai dan Analisis disimpan');
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Nilai $nilai)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Nilai $nilai)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Nilai $nilai)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Nilai $nilai)
    {
        //
    }
}
