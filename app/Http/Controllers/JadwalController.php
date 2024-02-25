<?php

namespace App\Http\Controllers;

use App\Models\Jadwal;
use Illuminate\Http\Request;

class JadwalController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            
        } catch (\Throwable $th) {
            //throw $th;
        }
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->data;
        try {
            Jadwal::updateOrCreate(
                [
                    'id' => $request['id'] ?? null,
                ],
                [
                    'tapel_id' => $request['tapel'],
                    'guru_id' => $request['guru_id'],
                    'rombel_id' => $request['rombel_id'],
                    'hari' => $request['hari'],
                    'jml_jam' => $request['jml_jam'],
                    'jamke' => $request['jamke'],
                    'is_active' => true
                ]
            );
            return back()->with('message', 'Jadwal disimpan');
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Jadwal $jadwal)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Jadwal $jadwal)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Jadwal $jadwal)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Jadwal $jadwal)
    {
        //
    }
}
