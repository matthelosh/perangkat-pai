<?php

namespace App\Http\Controllers;

use App\Models\Cp;
use Inertia\Inertia;
use App\Models\Elemen;
use App\Models\MateriAjar;
use Illuminate\Http\Request;

class KurikulumController extends Controller
{
    public function index() {
        try {
            $cps = Cp::with('elemens')->get();

            return Inertia::render('Dashboard/Perangkat/Kurikulum/index',[
                'cps' => $cps,
                'materis' => MateriAjar::with('kontens')->get(),
            ]);
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
