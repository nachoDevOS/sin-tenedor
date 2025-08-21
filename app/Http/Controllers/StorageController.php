<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManagerStatic as Image;

class StorageController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function store_image($file, $folder, $size = 1200){

        try {
            Storage::makeDirectory($folder.'/'.date('F').date('Y'));
            $base_name = Str::random(20).'day'.date('d').date('a');

            // imagen normal
            $extension = 'avif'/* $file->getClientOriginalExtension()*/;
            $filename = $base_name.'.'.$extension;
            $path =  $folder.'/'.date('F').date('Y').'/'.$filename;
            $image_resize = Image::make($file->getRealPath())->orientate();
            $image_resize->resize($size, null, function ($constraint) {
                $constraint->aspectRatio();
            });
            Storage::put($path, $image_resize->encode('avif', 80));

            // imagen banner
            $filename_banner = $base_name.'-banner.'.$extension;
            $image_resize = Image::make($file->getRealPath())->orientate();
            $image_resize->resize(900, null, function ($constraint) {
                $constraint->aspectRatio();
            });
            $path_banner = "$folder/".date('F').date('Y').'/'.$filename_banner;
            Storage::put($path_banner, $image_resize->encode('avif', 80));

            // imagen medium
            $filename_medium = $base_name.'-medium.'.$extension;
            $image_resize = Image::make($file->getRealPath())->orientate();
            $image_resize->resize(600, null, function ($constraint) {
                $constraint->aspectRatio();
            });
            $path_medium = "$folder/".date('F').date('Y').'/'.$filename_medium;
            Storage::put($path_medium, $image_resize->encode('avif', 80));

            // imagen small
            $filename_small = $base_name.'-small.'.$extension;
            $image_resize = Image::make($file->getRealPath())->orientate();
            $image_resize->resize(256, null, function ($constraint) {
                $constraint->aspectRatio();
            });
            $path_small = "$folder/".date('F').date('Y').'/'.$filename_small;
            Storage::put($path_small, $image_resize->encode('avif', 80));

            // imagen cropped
            $filename_cropped = $base_name.'-cropped.'.$extension;
            $image_resize = Image::make($file->getRealPath())->orientate();
            $image_resize->resize(null, 300, function ($constraint) {
                $constraint->aspectRatio();
            });
            $image_resize->resizeCanvas(300, 300);
            $path_cropped = "$folder/".date('F').date('Y').'/'.$filename_cropped;
            Storage::put($path_cropped, $image_resize->encode('avif', 80));

            if(env('FILESYSTEM_DRIVER') == 's3'){
                return env('AWS_ENDPOINT').'/'.env('AWS_BUCKET').'/'.env('AWS_ROOT').'/'.$path;    
            }
            return $path;
        } catch (\Throwable $th) {
            \Log::error('Error al guardar la imagen: ' . $th->getMessage());
            return null;
        }
    }
}
