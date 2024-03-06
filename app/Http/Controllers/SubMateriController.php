<?php

namespace App\Http\Controllers;

use App\Models\SubMateri;
use Illuminate\Http\Request;

class SubMateriController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->data;
        try {
            SubMateri::updateOrCreate(
                [
                    'id' => $data['id'] ?? null,
                ],
                [
                    'materi_id' => $data['materi_id'],
                    'fase' => $data['fase'],
                    'tingkat' => $data['tingkat'],
                    'label' => $data['label']
                ]
            );

            return back()->with('status', 'Submateri Disimpan');
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(SubMateri $subMateri)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SubMateri $subMateri)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SubMateri $subMateri)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, $id)
    {
        try {
            $submateri = SubMateri::findOrFail($id);
            $submateri->delete();
            return back()->with('status', 'Submateri Dihapus');
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
