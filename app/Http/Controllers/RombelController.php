<?php

namespace App\Http\Controllers;

use App\Models\Rombel;
use App\Models\Tapel;
use Illuminate\Http\Request;
use Inertia\Inertia;

class RombelController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = $request->user();
        $tapel = Tapel::where('status', 'active')->first();
        try {
            if (
                $user
                ->hasRole("admin")
            ) {
                $rombels = Rombel::whereIsActive(true)
                    ->with("sekolah")
                    ->get();
            } elseif (
                $user
                ->hasRole("gpai")
            ) {
                $rombels = Rombel::where(
                    "guru_id",
                    $user->userable->nip
                )
                    ->where('tapel', $tapel->kode)
                    ->with("sekolah", "siswas")
                    // ->whereIsActive(true)
                    ->get();
            }

            return Inertia::render("Dashboard/Utama/Rombel", [
                "rombels" => $rombels,
            ]);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function prevRombel(Request $request)
    {
        try {
            $sekolahId = $request->query('sekolah_id');
            $currentTapel = $request->query('currentTapel');
            $prevTapel = Tapel::where('id', '<', $currentTapel)->orderBy('id', 'DESC')->first();
            $prevRombels = Rombel::where('tapel', $prevTapel->kode)->where('sekolah_id',  $sekolahId)->with('siswas')->get();

            return response()->json(['rombels' => $prevRombels]);
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
            $data = json_decode($request->data);
            $kodes = explode("-", $data->kode);
            $rombel = Rombel::updateOrCreate(
                [
                    "id" => $data->id ?? null,
                ],
                [
                    "sekolah_id" => $kodes[1],
                    "guru_id" => auth()->user()->userable->nip,
                    "kode" => $data->kode,
                    "label" => $data->label,
                    "tapel" => $kodes[0],
                    "fase" => $data->fase,
                    "tingkat" => $data->tingkat,
                    "is_active" => "1",
                ]
            );

            return back();
        } catch (\Exception $e) {
            return back()->withErrors(["message" => $e->getMessage()]);
        }
    }

    public function detachSiswa(Request $request, $id, $siswa)
    {
        try {
            $rombel = Rombel::findOrFail($id);
            $detach = $rombel->siswas()->detach($siswa);

            // dd($detach);
            return back();
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Rombel $rombel)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Rombel $rombel)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, $id)
    {
        try {
            $rombel = Rombel::find($id);
            $rombel->delete();

            return back();
        } catch (\Exception $e) {
            return back()->withErrors("message", $e->getMessage());
        }
    }
}
