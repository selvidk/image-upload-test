<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Collection;

class ImageProcessing extends Controller
{
    public function index()
    {
        foreach (File::allFiles(public_path('asset/images')) as $image) {
            $image_name = $image->getFilename();
            $image_size = $image->getSize();

            $images[] = Collection::make([
                'file_name' => $image_name,
                'file_size' => $image_size / 1024,
            ]);
        }
        return view('index', ['images' => $images]);
    }

    public function uploadImage(Request $request)
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
            } else {
                $image = imagecreatefrompng($req_image);
            }
            imagejpeg($image, $new_image, 40);
        } else {
            $req_image->move($path, $new_image);
        }

        return redirect()->back()->with('success', 'Success');
    }

    // public function updateImage(Request $request)
    // {
    //     if (File::exists(public_path('asset/images/' . $request->id_image))) {
    //         if ($request->hasFile('update_image') && $request->has('update_name')) {
    //             $request->validate([
    //                 'image' => ['required', 'image', 'mimes:jpg,jpeg,png', 'max:999'],
    //             ]);

    //             return redirect()->back()->with('error', 'Success');

    //         }
    //         // File::delete(public_path('asset/images/' . $request->id_image));
    //         return redirect()->back()->with('success', 'Success....');
    //     } else {
    //         return redirect()->back()->with('failed', 'Image not exist....');
    //     }
    // }


    public function deleteImage($del_image)
    {
        if (File::exists(public_path('asset/images/' . $del_image))) {
            File::delete(public_path('asset/images/' . $del_image));
            return redirect()->back()->with('success', 'Success....');
        } else {
            return redirect()->back()->with('failed', 'Image not exist....');
        }
    }
}
