<?php

namespace App\Http\Controllers;

use App\Models\Tapel;
use Illuminate\Http\Request;

class TapelController extends Controller
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
    public function toggle(Request $request, $id)
    {
        try {
            $tapel = Tapel::findOrFail($id);
            if ($request->status == 'inactive') {
                $tapels = Tapel::get()->filter(fn ($q) => $q->status == 'active');
                if (count($tapels) === 1) {
                    return back()->withErrors(['error' => 'Tapel harus ada yang aktif']);
                }
            } else {
                Tapel::where('status', 'active')->update(['status' => 'inactive']);
                $tapel->update(['status' => $request->status]);
                return back()->with('message', 'Tapel ' . ($request->status == 'active' ? 'Diaktifkan' : 'Dinonaktifkan'));
            }
        } catch (\Throwable $th) {
            throw $th;
        }
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
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
