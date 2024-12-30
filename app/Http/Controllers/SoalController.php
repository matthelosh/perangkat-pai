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
}
