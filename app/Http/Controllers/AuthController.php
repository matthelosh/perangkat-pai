<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class AuthController extends Controller
{

    public function masuk(Request $request) {
        if ($request->user()) {
            $request->session()->regenerate();

            return redirect()->intended('/dashboard');
        } else {
            return Inertia::render("Masuk");
        }
    }
    public function authenticate(Request $request) : RedirectResponse 
    {
        try {
        $credentials = $request->validate([
            'name' => ['required'],
            'password' => ['required']
        ]);

        if ( Auth::attempt($credentials)) {
            $request->session()->regenerate();

            return redirect()->intended('dashboard');
        }

        return back()->withErrors([
            'name' => 'Periksan lagi username dan password Anda'
        ])->onlyInput('name');
        } catch(\Exception $e) {
            return back()->withErrors((['message' => $e->getMessage()]));
        }
    }
}
