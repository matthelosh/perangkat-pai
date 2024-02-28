<?php

namespace App\Http\Controllers;

use App\Models\Atp;
use App\Models\Prota;
use App\Models\Rombel;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ProtaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            // if ($)
            $rombel = Rombel::where('kode', $request->query('rombel'))->first();
            if ($request->query('mine')) {
                $atps = Atp::where('guru_id', auth()->user()->userable->nip)    
                            ->where('tingkat', $rombel->tingkat)
                            ->with('elemen')
                            ->get();
            } else {
                $atps = Atp::where('tingkat', $rombel->tingkat)
                            ->whereNull('guru_id')
                            ->with('elemen')
                            ->get();
            }
            return Inertia::render("Dashboard/Perangkat/Rencana/Prota", [
                // 'protas' => $protas,
                'atps' => $atps,
                'rombel' => $rombel,
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
    public function show(Prota $prota)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Prota $prota)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Prota $prota)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Prota $prota)
    {
        //
    }
}
