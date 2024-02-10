<?php

namespace App\Http\Controllers;

use App\Models\Sekolah;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SekolahController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $sekolahs = Sekolah::all();
            $user = User::find(auth()->user()->id);
            if($user->hasRole('gpai'))
            {
                $sekolahs = $sekolahs->where('npsn', $user->userable()->sekolah_id);
            }
            return Inertia::render("Dashboard/Utama/Sekolah", [
                'sekolahs' => $sekolahs
            ]);

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
            Sekolah::updateOrCreate(
                [
                    'id' => $request['id'] ?? null,
                ],
                [
                    'npsn' => $request['npsn'],
                    'nss' => $request['nss'] ?? null,
                    'nama'  => $request['nama'],
                    'alamat' => $request['alamat'],
                    'rt' => $request['rt'] ?? null,
                    'rw'  => $request['rw'] ?? null,
                    'desa'  => $request['desa'],
                    'kecamatan' => $request['kecamatan'] ?? 'Wagir',
                    'kode_pos'  => $request['kode_pos'] ?? '65158',
                    'kabupaten'  => $request['kabupaten'] ?? 'Malang',
                    'telp'  => $request['telp'] ?? null,
                    'email'  => $request['email'],
                    'website'  => $request['website'] ?? null,
                    'logo'  => $request['logo'] ?? null,
                    'nama_ks'  => $request['nama_ks'] ?? null,
                    'nip_ks'  => $request['nip_ks'] ?? null
                ]
                );
            return back();
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function import(Request $request)
    {
        try {
        foreach($request->sekolahs as $sekolah)
        {
            Sekolah::updateOrCreate(
                [
                    'id' => $sekolah['id'] ?? null,
                ],
                [
                    'npsn' => $sekolah['npsn'],
                    'nss' => $sekolah['nss'] ?? null,
                    'nama'  => $sekolah['nama'],
                    'alamat' => $sekolah['alamat'],
                    'rt' => $sekolah['rt'] ?? null,
                    'rw'  => $sekolah['rw'] ?? null,
                    'desa'  => $sekolah['desa'],
                    'kecamatan' => $sekolah['kecamatan'] ?? 'Wagir',
                    'kode_pos'  => $sekolah['kode_pos'] ?? '65158',
                    'kabupaten'  => $sekolah['kabupaten'] ?? 'Malang',
                    'telp'  => $sekolah['telp'] ?? null,
                    'email'  => $sekolah['email'],
                    'website'  => $sekolah['website'] ?? null,
                    'logo'  => $sekolah['logo'] ?? null,
                    'nama_ks'  => $sekolah['nama_ks'] ?? null,
                    'nip_ks'  => $sekolah['nip_ks'] ?? null
                ]
                );
        }
        return back();
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Sekolah $sekolah)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy( Request $request, $id)
    {
        $sekolah = Sekolah::findOrFail($id);
        $sekolah->delete();
        return back();
    }
}
