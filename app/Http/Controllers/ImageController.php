<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ImageController extends Controller
{
    public function upload(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:5120', // 5MB
        ]);

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = Str::random(20) . '.' . $file->getClientOriginalExtension();
            
            // Stocker dans le dossier public/images (assurez-vous que le dossier existe)
            $path = $file->storeAs('public/images', $filename);

            // Générer l'URL publique
            $url = Storage::url($path);

            return response()->json([
                'url' => $url,
                'path' => $path
            ]);
        }

        return response()->json(['error' => 'Aucun fichier trouvé.'], 400);
    }
}