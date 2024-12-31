<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ImageController extends Controller
{
    public function upload(Request $request)
    {
        try {
            $image = $request->file('image');
            $store = $image->storePubliclyAs('images/', Str::random(8) . '.' . $image->extension(), 'public');
            return response()->json(['url' => Storage::url($store)]);
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
