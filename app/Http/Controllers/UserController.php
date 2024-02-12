<?php

namespace App\Http\Controllers;

use App\Models\User;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            return Inertia::render("Dashboard/Settings/Account", [
                'users' => User::with('roles.permissions', 'permissions')->get(),
                'roles' => DB::table('roles')->get(),
                'permissions' => DB::table('permissions')->get(),
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
            $data = json_decode($request->data);
            $user = User::updateOrCreate(
                [
                    'email' => $data->email,
                ],
                [
                    'name' => $data->name,
                    'password' => Hash::make($data->password),
                    'userable_id' => $data->id,
                    'userable_type' => $request->query("type")
                ]
            );

            if(str_contains($request->query('type'), 'Guru')) {
                $user->assignRole('gpai');
            }
            return back();
        } catch (\Throwable $th) {
            throw $th;
        }
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        //
    }
}
