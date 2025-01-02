<?php

namespace App\Http\Controllers;

use App\Models\Soal;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SoalController extends Controller
{
    public function index(Request $request)
    {
        try {
            $soals = Soal::with('guru')->with('tp.elemen')->orderBy('updated_at', 'DESC')->get();
            $nip = $request->user()->userable->nip;
            if ($request->query('mine') == 'true') {
                $soals->filter(fn($soal) => $soal->guru_id == $nip);
            }
            return Inertia::render('Dashboard/Perangkat/Evaluasi/BankSoal', [
                'soals' => $soals
            ]);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function store(Request $request)
    {
        try {
            $soal = Soal::updateOrCreate(
                [
                    'id' => $request->id ?? null,
                ],
                [
                    'elemen_id' => $request->elemen_id,
                    'tingkat' => $request->tingkat,
                    'tp_id' => $request->tp_id,
                    'guru_id' => $request->user()->userable->nip,
                    'agama' => 'Islam',
                    'tipe' => $request->tipe,
                    'level' => $request->level,
                    'pertanyaan' => $request->pertanyaan,
                    'a' => $request->a,
                    'b' => $request->b,
                    'c' => $request->c,
                    'd' => $request->d,
                    'kunci' => $request->kunci
                ]
            );

            return back()->with('message', 'Soal disimpan');
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function destroy(Soal $soal, $id)
    {
        try {
            $soal::destroy($id);
            return \back()->with('message', 'Soal dihapus');
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
