<?php

namespace App;

use Illuminate\Http\UploadedFile;

class ImageUploader
{
    public function upload(UploadedFile $image)
    {
        $filenameWithExt = $image->getClientOriginalName();
        $fileExt = $image->getClientOriginalExtension();
        $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
        if(
            $fileExt !== 'jpg' && 
            $fileExt !== 'jpeg' && 
            $fileExt !== 'gif' && 
            $fileExt !== 'bmp' && 
            $fileExt !== 'png' && 
            $fileExt !== 'svg'
        ){
            return redirect('/posts/create')->with('error', 'Invalid file extension');
        }
        $filenameToStore = $filename . '_' . time() . '.' . $fileExt;
        if($image->storeAs('public/post_images', $filenameToStore)){
            return $filenameToStore;
        }
    }
}
