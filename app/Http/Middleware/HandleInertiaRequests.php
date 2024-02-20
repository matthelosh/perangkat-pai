<?php

namespace App\Http\Middleware;

use App\Models\Menu;
use App\Models\Tapel;
use App\Models\Semester;
use App\Models\Sekolah;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that's loaded on the first page visit.
     *
     * @see https://inertiajs.com/server-side-setup#root-template
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determines the current asset version.
     *
     * @see https://inertiajs.com/asset-versioning
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Defines the props that are shared by default.
     *
     * @see https://inertiajs.com/shared-data
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function share(Request $request): array
    {
        return array_merge(parent::share($request), [
            'user' => $request->user() ? $this->user($request->user()):  null,
            'navs' => $request->user() ? $this->menus($request->user()) : null,
            'sekolahs' => $request->user() ? $this->sekolahs() : null,
            'tapel' => Tapel::whereStatus('active')->first(),
            'semester' => Semester::whereStatus('active')->first(),
            'app_env' => $request->user() ? config('app.env') : null,
        ]);
    }

    private function menus($user) {
        // $user = User::where('id', $user_id)->with('roles')->first();
        $menus =  Menu::where('parent_id', 0)->where('roles','LIKE', '%'. auth()->user()->roles[0]->name.'%')->with('children', function($q) use($user) {
            $q->where('roles', 'LIKE',  '%'.$user->roles[0]->name.'%');
        })->get();

       return $menus;
    }

    private function user($user) : User {
        return User::where('id', $user->id)->with('roles','userable')->first();
    }

    private function sekolahs() {
        if (auth()->user()->hasRole('admin')) {
            return Sekolah::all();
        } else {
            return Sekolah::where('npsn', auth()->user()->userable->sekolah_id)->get();
        }
    }
}
