<?php

namespace App\Http\Controllers;

use App\Models\Atp;
use App\Models\Prosem;
use App\Models\Rombel;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ProsemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $rombel = Rombel::whereKode($request->query('rombel'))->with('jadwals')->first();
            if ($request->query('mine') == '1') {
                $atps = Atp::where('guru_id', auth()->user()->userable->nip)
                            ->whereRombelId($request->query('rombel'))
                            ->get();
            } else {
                $atps = Atp::whereNull('guru_id')
                            ->whereTingkat($rombel->tingkat)
                            ->get();
            }

            return Inertia::render('Dashboard/Perangkat/Rencana/Prosem', [
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
        //
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
    public function destroy(Prosem $prosem)
    {
        //
    }
}
