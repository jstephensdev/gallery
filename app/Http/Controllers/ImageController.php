<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Image;
use Illuminate\Support\Facades\Storage;

class ImageController extends Controller
{
    public function index(){
        return view('index');
    }

    public function show(){
        return Image::latest()->pluck('name')->toArray();
    }

     public function store(Request $request){
        // validate file
        if(!$request->hasFile('image')){
            return response()->json(['error' => 'No image present.'], 400);
        };

        $request->validate([
            'image' => 'file|image|required',
        ]);

        // process upload and save to storage
        $path = $request->file('image')->store('public/images');

        if(!$path){
            return response()->json(['error', 'The file could not be saved.'], 500);
        }

        // create image model
        $uploadedFile = $request->file('image');

        $image = Image::create([
            'name' => $uploadedFile->hashName(),
            'extension' => $uploadedFile->extension(),
            'size' => $uploadedFile->getSize()
        ]);

        // return image model to front end for viewing
        return $image;
    }

       public function destroy($image){
        $image = Image::where('name', '=', $image)->firstOrFail();
        $image->delete();
    }
}
