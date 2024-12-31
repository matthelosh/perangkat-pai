<?php

namespace App\Http\Controllers;

use App\Models\Elemen;
use Illuminate\Http\Request;

class ElemenController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $fase = (int) $request->tingkat > 4 ? 'C' : ((int) $request->tingkat > 2 ? 'B' : 'A');
            $elemens = Elemen::whereFase($fase)->get(['kode', 'label']);

            return \response()->json(['elemens' => $elemens], 200);
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

        Elemen::updateOrCreate(
            [
                'id' => $request['id'] ?? null,
            ],
            [
                'fase' => $request['fase'],
                'cp_id' => $request['cp_id'],
                'kode' => $request['kode'],
                'label' => $request['label'],
                'deskripsi_cp' => $request['deskripsi_cp'],
            ]
        );
        return back()->with('message', 'Deskripsi Cp diperbarui');
    }

    /**
     * Display the specified resource.
     */
    public function show(Elemen $elemen)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Elemen $elemen)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Elemen $elemen)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Elemen $elemen)
    {
        //
    }
}
