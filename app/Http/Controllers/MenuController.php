<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use Illuminate\Http\Request;
use Inertia\Inertia;

class MenuController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $menus = Menu::where('parent_id', 0)->with('children')->get();
        return Inertia::render("Dashboard/Settings/Menu", [
            'menus' => $menus
        ]);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $menu = json_decode($request->menu);
            // dd($menu);
            Menu::updateOrCreate(
                [
                    'id' => $menu->id ?? null
                ],
                [
                    'label' => $menu->label,
                    'url' => $menu->url,
                    'icon' => $menu->icon,
                    'parent_id' => $menu->parent_id,
                    'roles' => implode(",",$menu->roles)
                ]
                );

            return back();
        } catch (\Throwable $th) {
            throw $th;
        }
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Menu $menu)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Menu $menu)
    {
        //
    }
}
