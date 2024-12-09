<?php

namespace App\Http\Controllers;

use App\Models\Cp;
use App\Models\Atp;
use Inertia\Inertia;
use App\Models\Elemen;
use App\Models\Kaldik;
use App\Models\MateriAjar;
use App\Models\Rombel;
use App\Models\Tapel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AtpController extends Controller
{

    private function tapel()
    {
        return Tapel::whereStatus('active')->pluck('kode')->first();
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $kaldiks = Kaldik::whereIsLibur(true)->where('tapel_id', $this->tapel())->get();
            if ($request->query('fase')) {
                if ($request->query('mine') == 'true') {
                    $nip = auth()->user()->userable->nip;
                    $elemens  = Elemen::where('fase', $request->query('fase'))->with('tps', function ($q) use ($nip) {
                        $q->where('guru_id', $nip);
                    })->with('atps', function ($q) use ($nip) {
                        $q->where('guru_id', $nip);
                        $q->orderBy('semester', 'ASC');
                    })->get();


                    $atps = Atp::whereFase($request->query('fase'))
                        ->whereGuruId($nip)
                        ->with('elemen')
                        ->get();
                } else {
                    $elemens = Elemen::where('fase', $request->query('fase'))->with('tps')->with('atps', function ($q) {
                        $q->whereNull('guru_id');
                        $q->orderBy('semester', 'ASC');
                    })->get();

                    $atps = Atp::whereFase($request->query('fase'))
                        ->whereNull('guru_id')
                        ->with('elemen')
                        ->get();

                    // $rombels = null;
                }

                $cp = Cp::where('fase', $request->query('fase'))->first();
            }

            $tingkat = $request->query('fase') == 'A' ? ['1', '2'] : ($request->query('fase') == 'B' ? ['3', '4'] : ['5', '6']);

            $babs = MateriAjar::where('tingkat', $tingkat[0])->orWhere('tingkat', $tingkat[1])->with('kontens')->get();
            return Inertia::render('Dashboard/Perangkat/Rencana/Atp', [
                'elemens' => $elemens,
                'babs' => $babs,
                'cp' => $cp,
                'kaldiks' => $kaldiks,
                'atps' => $atps,
                // 'rombels' => $rombels
            ]);
        } catch (\Throwable $th) {
            // throw $th;
            return back()->withErrors("status", $th->getMessage());
        }
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            // dd(!$request->query('mine'));
            $data = $request->data;
            Atp::updateOrCreate(
                [
                    'id' => $data['id'] ?? null,
                    'kode' => $this->kode($request->query('mine'), $data['elemen_id']),
                ],
                [
                    'guru_id' => $request->query('mine') ? auth()->user()->userable->nip : null,

                    'elemen_id' => $data['elemen_id'],
                    'tingkat' => $data['tingkat'],
                    'fase' => (int) $data['tingkat'] > 4 ? 'C' : ($data['tingkat'] > 2 ? 'B' : 'A'),
                    'semester' => $data['semester'],
                    'aw' => $data['aw'],
                    'materi' => $data['materi'],
                    'tps' => implode(";", $data['tps']),
                    'konten' => implode(";", $data['konten']),
                    'asesmen' => $data['asesmen'],
                    'p5' => $data['p5']
                ]
            );

            return back()->with('message', 'ATP disimpan');
        } catch (\Throwable $th) {
            // throw $th;
            return back()->withErrors(['message' => $th->getMessage()]);
        }
    }

    private function kode($mine, $elemen_id)
    {
        if ($mine) {
            $latest = Atp::where('guru_id', auth()->user()->userable->nip)->where('elemen_id', $elemen_id)->pluck('kode')->last();
        } else {
            $latest = Atp::whereNull('guru_id')->where('elemen_id', $elemen_id)->pluck('kode')->last();
        }

        if ($latest) {
            $kode = explode("-", $latest);
            $no = intval($kode[count($kode) - 1]);
        } else {
            $no = 0;
        }

        return !$mine ? $elemen_id . '-' . $no + 1 : auth()->user()->userable->nip . '-' . $elemen_id . '-' . $no + 1;
    }
    /*
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        try {
            $atp = Atp::findOrFail($id);
            $atp->update(['aw' => $request->aw]);

            return back()->with('message', $atp);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, $id)
    {
        try {
            $atp = Atp::findOrFail($id);
            $atp->delete();

            return back()->with('status', 'Atp Dihapus');
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function destroyAll(Request $request)
    {
        try {
            if ($request->query('mine')) {

                DB::table('atps')->where('guru_id', '=', auth()->user()->userable->nip)->delete();

                return back()->with('message', 'Atp dibersihkan');
            }
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
