<?php

namespace App\Http\Controllers;

use App\Models\Semester;
use App\Models\Tapel;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function home(Request $request)
    {
        try {
            $data = [];
            $data['tapels'] = Tapel::all();
            $data['semesters'] = Semester::all();
            return Inertia::render('Dashboard/Home', [
                'data' => $data,
            ]);
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
