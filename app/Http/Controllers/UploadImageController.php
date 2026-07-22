<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Laravel\Facades\Image;
use Intervention\Image\Encoders\JpegEncoder;

class UploadImageController extends Controller
{
    public function uploadimage(Request $request)
    {
        if ($request->hasFile('upload')) {

            $originName = $request->file('upload')->getClientOriginalName();

            $fileName = pathinfo($originName, PATHINFO_FILENAME);

            $extension = strtolower($request->file('upload')->getClientOriginalExtension());

            // Simpan sebagai jpg selepas compress
            $fileName = $fileName . '_' . time() . '.jpg';

            // Decode gambar
            $new_img = Image::decode($request->file('upload'));

            // Resize (kekalkan ratio)
            $new_img->scaleDown(width: 1200);

            // Encode JPEG quality 85
            $new_img = $new_img->encode(new JpegEncoder(quality: 85));

            // Simpan ke storage
            Storage::disk('public')->put(
                'images/' . $fileName,
                $new_img
            );

            $url = asset('storage/images/' . $fileName);

            return response()->json([
                'fileName' => $fileName,
                'uploaded' => 1,
                'url' => $url
            ]);
        }

        return response()->json([
            'uploaded' => 0,
            'error' => [
                'message' => 'Tiada fail dihantar.'
            ]
        ]);
    }
}