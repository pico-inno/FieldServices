<?php

namespace App\Services\file;

use Exception;
use Ramsey\Uuid\Uuid;
use Illuminate\Support\Facades\Auth;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class FileServices
{
    public static function upload($file,$path='/' ,$nameAlgo='uuid',$isImage=true,$service='local',$type=''){
        self::validate($file);
        $extension = $file->getClientOriginalExtension();
        $uuid = Uuid::uuid4()->toString();
        if($nameAlgo =='uuid'){
            $fileName = $uuid . '.'.$extension;
        }elseif('ts'){
            $userId=Auth::user()->id;
            $time=time();
            $fileName = $uuid.'_'.$userId.'-'.$time.'.'.$extension;
        }
        try {
            if($service === 'local'){
                if($isImage){
                    $file=self::convertToWebp($file);
                    $userId = Auth::user()->id;
                    $time = time();
                    $fileName = $uuid . '_' . $userId . '-' . $time.'.webp';
                    Storage::disk('public')->put($path . $fileName, $file);
                }else{
                    Storage::disk('public')->put($path . $fileName, file_get_contents($file));
                }
            }else{
                dd('Services Not Available');
            }
            return $fileName;
        } catch (\Throwable $th) {
           return throw new Exception($th->getMessage());
        }
    }
    public static function delete($filename,$path='/'){
        return Storage::delete($path.$filename);
    }
    public static function validate($file){
        Validator::make([
            'file'=>$file
        ],[
            'file' => 'mimes:jpg,bmp,png,webp'
        ])->validate();
        $file = $file;
        $allowedContentTypes = ['image/jpeg', 'image/png', 'image/bmp','image/webp']; // Add allowed content types
        $uploadedContentType = $file->getMimeType();
        if (!in_array($uploadedContentType, $allowedContentTypes)) {
            return throw new Exception('Invalid file content type,400');
        }
    }
    public static function convertToWebp($file){
        $image = Image::make(file_get_contents($file)); // Load the original image
        $webpImage = $image->encode('webp', 90);
        return $webpImage;
    }
}
