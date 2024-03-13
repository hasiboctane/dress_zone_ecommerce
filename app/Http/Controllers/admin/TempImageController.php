<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\TempImage;
use Illuminate\Http\Request;

class TempImageController extends Controller
{
    public function create(Request $request) {
        $image = $request->image;
        if(!empty($image)) {
            $ext = $image->getClientOriginalExtension();
            $imageName= time().'.'.$ext;
            $image->move(public_path().'/temp', $imageName);
            $tempImage = new TempImage();
            $tempImage->name = $imageName;
            $tempImage->save();

            return response()->json([
                'status' => true,
                'image_id' => $tempImage->id,
                'message' => 'Image uploaded successfully.'
            ]);
        }
    }
}
