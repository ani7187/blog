<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class ImageController extends Controller
{
    public function show($imageName)
    {
        // Get the path to the image file from storage
        $imagePath = storage_path('app/public/images/' . $imageName);

        // Check if the image exists
        if (!Storage::exists('public/images/' . $imageName)) {
            // Return a 404 response if the image is not found
            return response()->json(['error' => 'Image not found'], 404);
        }

        // Get the file's MIME type
        $mimeType = mime_content_type($imagePath);

        // Return the image as a response with the appropriate MIME type
        return response()->file($imagePath, [
            'Content-Type' => $mimeType,
        ]);
    }
}
