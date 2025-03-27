<?php

namespace App\Http\Controllers;

use App\Models\Elemen;
use App\Models\Rombel;
use App\Models\Siswa;
use App\Models\Tp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\DB;

class RaporController extends Controller
{
    // Get Siswa from RaporSD
    public function getSiswa(Request $request)
    {
        $response = Http::withHeaders([
            'X-CLIENT-TOKEN' => env('CLIENT_TOKEN')
        ])->get('http://raporsd.test/api/posts');
        return $response->unauthorized() ? 'Tidak berhak' : $response->collect();
    }

    /**
     *  @return getRombel use to get rombels data
     * @request
     */
    public function getRombel(Request $request)
    {
        $raporHost = env('APP_ENV') == 'local' ? 'https://raporsd.test' : env('RAPOR_URI');
        $response = Http::withHeaders([
            'X-CLIENT-TOKEN' => env('CLIENT_TOKEN')
        ])
            ->withQueryParameters(
                [
                    'npsn' => $request->query('npsn')
                ]
            )->get($raporHost . '/api/rombel');

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
                'X-CLIENT-TOKEN' => env('CLIENT_TOKEN')
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
                $raporTps = $response->json('tps');
                // dd($raporTps);
                return \response()->json([
                    'raporTps' => $raporTps,
                    'localTps' => $localTp
                ])->setStatusCode(200);
            };
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    // Sync Tp Rapor
    public function syncTp(Request $request)
    {
        try {
            $creates = [];
            DB::table("tps")->truncate();
            foreach ($request->all() as $tp) {
                $kode = \explode(",", $tp['kode']);
                // $fase = \in_array(end($kode), ['1', '2']) ? 'A' : (\in_array(end($kode), ['3', '4']) ? 'B' : 'C');
                $elemen = Elemen::where('fase', $tp['fase'])->where('label', $tp['elemen'])->value('kode');
                // dd($elemen);
                $tp = Tp::create([
                    'kode' => $tp['kode'],
                    'fase' => $tp['fase'],
                    'elemen_id' => $elemen,
                    'guru_id' => null,
                    'kompetensi' => '-',
                    'materi' => '-',
                    'teks' => $tp['teks']
                ]);

                array_push($creates, $tp->id);
            }
            // dd(count($creates));
            return back()->with('message', 'Sinkron TP berhasil');
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    // Syn Nilai Rapor
    public function storeNilai(Request $request)
    {
        $payloads = $request->siswas;
        // dd($payloads, env('RAPOR_URI'));
        try {
            $response = Http::withHeaders([
                'X-CLIENT-TOKEN' => env('CLIENT_TOKEN'),  // Changed from RAPOR_TOKEN
                'Content-Type' => 'application/json'
            ])
                ->withQueryParameters($request->query())
                ->post(env('RAPOR_URI') . '/api/nilai/pts/store', ['siswas' => $payloads]);

            // Dump everything for debugging
            // dd([
            //     'raw_response' => $response,
            //     'status' => $response->status(),
            //     'body' => $response->body(),
            //     'json' => $response->json(),
            //     'headers' => $response->headers(),
            //     'request_data' => [
            //         'url' => env('RAPOR_URI') . '/api/nilai/pts/store',
            //         'payloads' => $payloads,
            //         'query' => $request->query(),
            //         'token' => env('RAPOR_TOKEN')
            //     ]
            // ]);

            if ($response->ok()) {
                $responseData = $response->json();
                return back()->with('message', $responseData['message']);
            }

            return back()->with('error', 'Failed to store nilai');
        } catch (\Throwable $th) {
            report($th);
            return back()->with('error', $th->getMessage());
        }
    }
}
