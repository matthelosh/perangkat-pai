<?php

namespace App\Http\Controllers;

use App\Models\Guru;
use App\Models\Sekolah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class GuruController extends Controller
{

    public function __construct()
    {
        $this->middleware(['role:admin'])->only('index');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $role = auth()->user()->roles[0]['name'];
        try {
            if ($role == 'admin') {
                $gurus = Guru::with('sekolahs', 'account')->paginate(25);
            } else if ($role == 'gpai') {
                $gurus = Guru::where('id', auth()->user()->userable_id)->with('sekolahs', 'account')->get();
            }

            return Inertia::render("Dashboard/Utama/Guru", [
                'gurus' => $gurus,
            ]);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    function storeFoto($foto, $nama)
    {
        $store = $foto->storePubliclyAs('perangkat-pai/img/guru', $nama . '.' . $foto->extension(), 's3');

        return Storage::disk('s3')->url($store);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            // dd($request->all());
            $data = json_decode($request->data);
            $foto = $request->file("fileFoto") ? $this->storeFoto($request->file("fileFoto"), $data->nama) : ($data->foto ?? null);
            // dd($data);
            $guru = Guru::updateOrCreate(
                [
                    'id' => $data->id ?? null,
                ],
                [
                    'nip'  => $data->nip,
                    'nuptk' => $data->nuptk ?? null,
                    'no_sertifikat' => $data->no_sertifikat ?? null,
                    'nrg' => $data->nrg ?? null,
                    'gelar_depan' => $data->gelar_depan ?? null,
                    'nama' => $data->nama,
                    'gelar_belakang' => $data->gelar_belakang ?? null,
                    'jk' => $data->jk,
                    'foto' => $foto,
                    'hp' => $data->hp ?? null,
                    'alamat' => $data->alamat ?? null,
                    'sekolah_id' => count($data->sekolahs) > 0 ? $data->sekolahs[0]->npsn : $data->sekolah_id[0],
                    'status_kepegawaian' => $data->status_kepegawaian,
                    'belajar_id' => $data->belajar_id
                ]
            );
            if (count($data->sekolahs) > 1) {
                foreach ($data->sekolahs as $sekola) {
                    $sekolah = Sekolah::whereId($sekola->id)->first();
                    $sekolah->gurus()->attach($guru->id);
                }
            } else {
                foreach ($data->sekolah_id as $npsn) {
                    $sekolah = Sekolah::whereNpsn($npsn)->first();
                    $sekolah->gurus()->attach($guru->id);
                }
            }
            // $sekolah_ids = [];

            return back()->with('status', 'Data Guru Disimpan');
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    /**
     * Bulk Store resource
     */
    public function import(Request $request)
    {
        try {
            $datas = json_decode($request->datas);
            foreach ($datas as $data) {
                Guru::updateOrCreate(
                    [
                        'id' => $data->id ?? null,
                    ],
                    [
                        'nip'  => $data->nip,
                        'nuptk' => $data->nuptk ?? null,
                        'no_sertifikat' => $data->no_sertifikat ?? null,
                        'nrg' => $data->nrg ?? null,
                        'gelar_depan' => $data->gelar_depan ?? null,
                        'nama' => $data->nama,
                        'gelar_belakang' => $data->gelar_belakang ?? null,
                        'jk' => $data->jk,
                        'foto' => $data->foto ?? null,
                        'hp' => $data->hp ?? null,
                        'alamat' => $data->alamat ?? null,
                        'sekolah_id' => $data->sekolah_id,
                        'status_kepegawaian' => $data->status_kepegawaian,
                        'belajar_id' => $data->belajar_id
                    ]
                );
            }
        } catch (\Throwable $th) {
            throw $th;
        }
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Guru $guru)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, $id)
    {
        $guru = Guru::findOrFail($id);
        $guru->delete();

        return back();
    }
}
