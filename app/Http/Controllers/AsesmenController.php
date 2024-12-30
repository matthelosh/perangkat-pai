<?php

namespace App\Http\Controllers;

use App\Models\Asesmen;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;

class AsesmenController extends Controller
{
    public function index(Request $request)
    {
        try {
            $asesmens = Asesmen::whereTapel(\tapel()->kode)
                ->whereSemester(\semester()->kode)
                ->with('soals')
                ->get();

            return Inertia::render('Dashboard/Utama/Asesmen', [
                'asesmens' => $asesmens
            ]);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function store(Request $request)
    {
        try {
            $store = Asesmen::updateOrCreate(
                [
                    'id' => $request->id ?? null,
                ],
                [
                    'kode' => Str::random(8),
                    'label' => $request->label,
                    'tipe' => $request->tipe,
                    'tingkat' => $request->tingkat,
                    'tapel' => \tapel()->kode,
                    'semester' => \semester()->kode,
                    'jml_soal' => implode(",", $request->jml_soal),
                    'kunci' => $request->kunci
                ]
            );

            return back()->with('message', 'Asesmen disimpan');
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function destroy(Request $request, $id)
    {
        try {
            $asesmen = Asesmen::findOrFail($id);
            // dd($asesmen);
            $delAnalisis = $asesmen->analises()->delete();
            // dd($delAnalisis);
            $delNilai = $asesmen->nilais()->delete();
            // dd($delNilai);
            $delSoal = $asesmen->soals()->detach();
            $asesmen->delete();

            return response()->json(['message' => 'Asesmen dihapus']);
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
