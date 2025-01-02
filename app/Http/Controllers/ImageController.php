<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Drivers\Imagick\Driver;
use Intervention\Image\ImageManager;

class ImageController extends Controller
{
    private function isSvg($file)
    {
        return $file->getClientOriginalExtension() === 'svg' ||
            $file->getMimeType() === 'image/svg+xml' ||
            $file->getClientMimeType() === 'image/svg+xml' ||
            str_contains($file->getMimeType(), 'svg');
    }

    public function upload(Request $request)
    {
        try {
            $request->validate([
                'image' => 'required|image|mimes:jpeg,png,jpg,webp',
            ]);
            $file = $request->file('image');


            $manager = new ImageManager(new Driver());
            $image = $manager->read($file);
            $maxWidth = 720;

            if ($image->width() > $maxWidth) {
                $aspectRatio = $image->width() / $image->height();
                $newHeight = intval($maxWidth / $aspectRatio);
                $image->resize($maxWidth, $newHeight);
            }

            $filename = 'images/' . Str::random(8) . '.webp';
            $encodedImage = $image->toWebp(75);
            Storage::disk('public')->put($filename, $encodedImage);

            return response()->json(['url' => Storage::url($filename)]);
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
