<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class PelaksanaanController extends Controller
{
    public function index(Request $request) {
        try {
            return Inertia::render('Dashboard/Perangkat/Pelaksanaan/index');
        } catch (\Throwable $th) {
            //throw $th;
        }
    }
}
