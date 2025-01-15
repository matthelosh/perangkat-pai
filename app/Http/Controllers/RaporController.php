<?php

namespace App\Http\Controllers;

use App\Models\Rombel;
use App\Models\Siswa;
use App\Models\Tp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\Response;

class RaporController extends Controller
{
    // Get Siswa from RaporSD
    public function getSiswa(Request $request)
    {
        $response = Http::withHeaders([
            'X-CLIENT-TOKEN' => env('RAPOR_TOKEN')
        ])->get('http://raporsd.test/api/posts');
        return $response->unauthorized() ? 'Tidak berhak' : $response->collect();
    }

    /**
     *  @return getRombel use to get rombels data
     * @request
     */
    public function getRombel(Request $request)
    {
        $response = Http::withHeaders([
            'X-CLIENT-TOKEN' => env('RAPOR_TOKEN')
        ])
            ->withQueryParameters(
                [
                    'npsn' => $request->query('npsn')
                ]
            )->get(env('RAPOR_URI') . '/api/rombel');

        if ($response->failed()) {
            return $response->status();
        } else {
            // $data = $response->object();
            return \response()->json($response->json('rombels'))->setStatusCode(200);
        };
    }

    public function syncRombel(Request $request)
    {
        try {
            foreach ($request->datas as $rombel) {
                // $siswas = array_map(fn($s) => $s['id'], $rombel['siswas']);
                if (! Rombel::whereKode($rombel['kode'])->first()) {
                    $newRombel = new Rombel($rombel);
                    $newRombel->guru_id = $request->user()->userable->nip;
                    $newRombel->save();
                    foreach ($rombel['siswas'] as $siswa) {
                        $sis = Siswa::where('nisn', $siswa['nisn'])->first();
                        if (! $sis) {
                            $sis = new Siswa($siswa);
                            $sis->save();
                        }
                        $sis->rombels()->attach($newRombel->id);
                    }
                }
            }
            return back()->with('message', 'Data Rombel disinkron');
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    // Sync TP
    public function getTp(Request $request)
    {
        try {
            $localTp = Tp::where('fase', $request->query('fase'))->with('elemen')->get();
            $response = Http::withHeaders([
                'X-CLIENT-TOKEN' => env('RAPOR_TOKEN')
            ])
                ->withQueryParameters(
                    [
                        'fase' => $request->query('fase')
                    ]
                )->get(env('RAPOR_URI') . '/api/tps');

            if ($response->failed()) {
                return $response->status();
            } else {
                // $data = $response->object();
                return \response()->json([
                    'raporTps' => $response->json('tps'),
                    'localTps' => $localTp
                ])->setStatusCode(200);
            };
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
