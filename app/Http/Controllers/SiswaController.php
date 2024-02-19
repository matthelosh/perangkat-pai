<?php

namespace App\Http\Controllers;

use App\Http\Requests\SiswaRequest;
use App\Models\Siswa;
use Illuminate\Http\Request;

class SiswaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $data = json_decode($request->data);
            $siswa = Siswa::updateOrCreate(
                [
                    "nisn" => $data->nisn,
                ],
                [
                    "nama" => $data->nama,
                    "jk" => $data->jk,
                    "sekolah_id" => $data->sekolah_id,
                ]
            );

            if (!$siswa->rombels()->exists()) {
                $siswa->rombels()->attach($data->rombel_id);
            }
            return back();
        } catch (\Exception $e) {
            return back()->withErrors($e->getMessage());
        }
    }

    public function impor(Request $request)
    {
        try {
            $datas = json_decode($request->datas);
            foreach ($datas as $data) {
                $siswa = Siswa::updateOrCreate(
                    [
                        "nisn" => $data->nisn,
                    ],
                    [
                        "nama" => $data->nama,
                        "jk" => $data->jk,
                        "sekolah_id" => $request->sekolah_id,
                    ]
                );

                if (!$siswa->rombels()->exists()) {
                    $siswa->rombels()->attach($request->rombel_id);
                }
            }

            return back();
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Siswa $siswa)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Siswa $siswa)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Siswa $siswa)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Siswa $siswa)
    {
        //
    }
}
