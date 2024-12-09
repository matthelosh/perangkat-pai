<?php

namespace App\Http\Controllers;

use App\Models\Semester;
use Illuminate\Http\Request;

class SemesterController extends Controller
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
    public function toggle(Request $request, $id)
    {
        try {
            $semester = Semester::findOrFail($id);
            if ($request->status == 'inactive') {
                $semesters = Semester::get()->filter(fn ($q) => $q->status == 'active');
                if (count($semesters) === 1) {
                    return back()->withErrors(['errors' => 'Semester harus ada yang aktif']);
                }
            } else {
                Semester::where('status', 'active')->update(['status' => 'inactive']);
                $semester->update(['status' => $request->status]);
                return back()->with('message', 'Semester ' . ($request->status == 'active' ? 'Diaktifkan' : 'Dinonaktifkan'));
            }
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Semester $semester)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Semester $semester)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Semester $semester)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Semester $semester)
    {
        //
    }
}
