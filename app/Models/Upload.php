<?php

/**
 * Clevada: #1 Free Business Suite and Website Builder.
 *
 * Copyright (C) 2021  Chimilevschi Iosif Gabriel, https://clevada.com.
 *
 * LICENSE:
 * Clevada is licensed under the GNU General Public License v3.0
 * Permissions of this strong copyleft license are conditioned on making available complete source code 
 * of licensed works and modifications, which include larger works using a licensed work, under the same license. 
 * Copyright and license notices must be preserved. Contributors provide an express grant of patent rights.
 *    
 * @copyright   Copyright (c) 2021, Chimilevschi Iosif Gabriel, https://clevada.com.
 * @license     https://opensource.org/licenses/GPL-3.0  GPL-3.0 License.
 * @version     2.1.1
 * @author      Chimilevschi Iosif Gabriel <office@clevada.com>
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Image;
use File;

class Upload extends Model
{
    /**
     * Upload file in 'public/uploads/YYYYMM folder
     * YYYYMM - current year and month
     * use original file name and add a random string (10 chars) in front of filename
     * return new filename (including YYYYMM subfolder)
     * @return string
     */

    public static function upload_image($request, $filename)
    {
        if ($request->hasFile($filename)) {

            $width = '1200';
            $height = '600';

            $file = $request->file($filename);

            $originalname = $file->getClientOriginalName();
            $extension = strtolower($file->extension());

            if ($extension == 'jpg' or $extension == 'jpeg' or $extension == 'png' or $extension == 'gif' or $extension == 'webp' or $extension == 'bmp') {

                $new_filename = Str::random(12) . '-' . $originalname;

                $subfolder = date("Ym");

                if (!File::isDirectory('uploads' . DIRECTORY_SEPARATOR . $subfolder)) {
                    File::makeDirectory('uploads' . DIRECTORY_SEPARATOR . $subfolder, 0777, true, true);
                }

                $path_large = 'uploads' . DIRECTORY_SEPARATOR . $subfolder . DIRECTORY_SEPARATOR . $new_filename;

                $path_thumb = 'uploads' . DIRECTORY_SEPARATOR . $subfolder . DIRECTORY_SEPARATOR . 'thumb_' . $new_filename;
                $path_thumb_square = 'uploads' . DIRECTORY_SEPARATOR . $subfolder . DIRECTORY_SEPARATOR . 'thumb_square_' . $new_filename;

                Image::make($file)->resize($width, $height, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                })->save($path_large); // large image

                Image::make($file)->resize(450, 450, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                })->save($path_thumb);  // thumb 

                Image::make($file)->fit(450, 450)->save($path_thumb_square);  // thumb square

                return $subfolder . DIRECTORY_SEPARATOR . $new_filename;
            } else return null; // invalid extension
        }
    }


    // create avatar image
    public static function avatar($request, $filename)
    {
        if ($request->hasFile($filename)) {
            $file = $request->file($filename);
            $originalname = $file->getClientOriginalName();

            $extension = strtolower($file->extension());

            if ($extension == 'jpg' or $extension == 'jpeg' or $extension == 'png' or $extension == 'gif' or $extension == 'webp' or $extension == 'bmp') {

                $new_filename = Str::random(12) . '-' . $originalname;

                $subfolder = date("Ym");
                if (!File::isDirectory('uploads' . DIRECTORY_SEPARATOR . $subfolder)) {
                    File::makeDirectory('uploads' . DIRECTORY_SEPARATOR . $subfolder, 0777, true, true);
                }

                $path_large = 'uploads' . DIRECTORY_SEPARATOR . $subfolder . DIRECTORY_SEPARATOR . $new_filename;
                $path_thumb = 'uploads' . DIRECTORY_SEPARATOR . $subfolder . DIRECTORY_SEPARATOR . 'thumb_' . $new_filename;

                // create images with fit (resize and crop)
                Image::make($file)->fit(350)->save($path_large); // large image 350x350
                Image::make($file)->fit(120)->save($path_thumb); // thumb image (120x120)

                return $subfolder . DIRECTORY_SEPARATOR . $new_filename;
            } else return null; // invalid extension
        }
    }


    //  upload file (any type)
    public static function upload_file(Request $request, $filename)
    {
        $file = $request->file($filename);
        $originalname = $file->getClientOriginalName();
        $originalname =  str_replace(' ', '_', $originalname);
        $new_filename = Str::random(12) . '-' . $originalname;

        $subfolder = date("Ym");
        if (!File::isDirectory('uploads' . DIRECTORY_SEPARATOR . $subfolder)) {
            File::makeDirectory('uploads' . DIRECTORY_SEPARATOR . $subfolder, 0777, true, true);
        }

        $path = 'uploads' . DIRECTORY_SEPARATOR . $subfolder . DIRECTORY_SEPARATOR . $new_filename;
        move_uploaded_file($file, $path);

        return $subfolder . DIRECTORY_SEPARATOR . $new_filename;
    }
}
