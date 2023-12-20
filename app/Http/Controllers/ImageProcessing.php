<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class ImageProcessing extends Controller
{
    public function index()
    {
        foreach (File::allFiles(public_path('asset/images')) as $image) {
            $image_name = $image->getFilename();
            $image_size = $image->getSize();

            $images[] = collect([
                'file_name' => $image_name,
                'file_size' => $image_size / 1024,
            ]);
        }

        return view('index', ['images' => $images]);
    }

    public function compressFile($req_image, $get_image_type, $new_image, $path)
    {
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
    }

    public function uploadImage(Request $request)
    {
        $request->validate([
            'image' => ['required', 'image', 'mimes:jpg,jpeg,png', 'max:999'],
            'image_name' => ['required', 'string', 'max:10'],
        ]);

        $req_image      = $request->file('image');
        $get_image_type = $req_image->getMimeType();
        $get_image_name = $request->image_name;
        $new_image_name = time() . '_' . $get_image_name . '.' . explode('/', $get_image_type)[1];

        $path           = public_path('asset/images/');
        $new_image      = $path . $new_image_name;

        $this->compressFile($req_image, $get_image_type, $new_image, $path);

        return redirect()->back()->with('success', 'Success');
    }

    public function updateImage(Request $request)
    {
        if (File::exists(public_path('asset/images/' . $request->id_image))) {
            $old_name = explode('_', explode('.', $request->id_image)[0])[1];

            if ($request->hasFile('update_image') && $request->has('update_name')) {
                $request->validate([
                    'update_image' => ['required', 'image', 'mimes:jpg,jpeg,png', 'max:999'],
                    'update_name'   => ['required', 'string', 'max:10'],
                ]);

                $req_image      = $request->file('update_image');
                $get_image_type = $req_image->getMimeType();

                if ($request->update_name == $old_name) {
                    $new_image_name = $request->id_image;
                } else {
                    $get_image_name = $request->update_name;
                    $new_image_name = explode('_', $request->id_image)[0] . '_' . $get_image_name . '.' . explode('/', $get_image_type)[1];
                }

                $path           = public_path('asset/images/');
                $new_image      = $path . $new_image_name;

                $this->compressFile($req_image, $get_image_type, $new_image, $path);

                if ($request->update_name != $old_name) {
                    File::delete(public_path('asset/images/' . $request->id_image));
                }
            } else {
                $request->validate([
                    'update_name'   => ['required', 'string', 'max:10'],
                ]);
                rename(public_path('asset/images/' . $request->id_image), public_path('asset/images/' . explode('_', $request->id_image)[0] . '_' . $request->update_name . '.' . explode('.', $request->id_image)[1]));
            }
            // File::delete(public_path('asset/images/' . $request->id_image));
            return redirect()->back()->with('success', 'Success....');
        } else {
            return redirect()->back()->with('failed', 'Image not exist....');
        }
    }


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
