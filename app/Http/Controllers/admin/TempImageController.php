<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\TempImage;
use Illuminate\Http\Request;
use Intervention\Image\ImageManager;

class TempImageController extends Controller
{
    public function create(Request $request) {
        $image = $request->image;
        if(!empty($image)) {
            $ext = $image->getClientOriginalExtension();
            $imageName= time().'.'.$ext;
            $tempImage = new TempImage();
            $tempImage->name = $imageName;
            $tempImage->save();
            $image->move(public_path().'/temp', $imageName); //was in line 16
            // Generate Thumbnail
            $sourcePath = public_path().'/temp/'.$imageName;
            $destPath = public_path().'/temp/thumbnail/'.$imageName;
            $image = ImageManager::gd()->read($sourcePath)->cover(300,275);
            $image->save($destPath);

            return response()->json([
                'status' => true,
                'image_id' => $tempImage->id,
                'imagePath' => asset('/temp/thumbnail/'.$imageName),
                'message' => 'Image uploaded successfully.'
            ]);
        }
    }
}
