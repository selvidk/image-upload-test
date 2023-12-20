<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class ImageProcessing extends Controller
{
    public function index()
    {
        $images = File::allFiles(public_path('asset/images'));
        return view('index')->with(array('images' => $images));
    }

    public function imageUpload(Request $request)
    {
        $request->validate([
            'image' => ['required', 'image', 'mimes:jpg,jpeg,png', 'max:999'],
        ]);

        $req_image      = $request->file('image');
        $get_image_name = $req_image->getClientOriginalName();
        $new_image_name = time() . '_' . $get_image_name;
        $get_image_type = $req_image->getMimeType();

        $path           = public_path('asset/images/');
        $new_image      = $path . '/' . $new_image_name;

        $get_image_size = $req_image->getSize();
        $kb_size        = $get_image_size / 1024;

        if ($kb_size > 100) {
            if ($get_image_type == 'image/jpeg' || $get_image_type == 'image/jpg') {
                $image = imagecreatefromjpeg($req_image);
                imagejpeg($image, $new_image, 40);
            } else {
                $image = imagecreatefrompng($req_image);
                imagepng($image, $new_image, 0);
            }
        } else {
            $req_image->move($path, $new_image);
        }

        return redirect()->back()->with('success', 'Success');
    }

    public function imageDelete(Request $request)
    {
        File::delete(public_path('asset/images/' . $request->image_del));

        return redirect()->back()->with('success', 'Success....');
    }
}
