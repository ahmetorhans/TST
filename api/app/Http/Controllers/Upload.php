<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Storage;
use Intervention\Image\ImageManagerStatic as Image;



class Upload extends Controller
{
    

    /**
     * Upload file
     *
     * @param Request $request
     * @return json array
     */
    public function uploadFile(Request $request){
        
        $image = $request->file('file');
        $filename  = time() . '.' . $image->getClientOriginalExtension();
        $path = public_path('/app/files/'. $filename);
        $thumb = public_path('app/files/thumb/'. $filename);
        
        Image::make($image->getRealPath())->resize(1000, null, function ($constraint) {
            $constraint->aspectRatio();
        })->save($path,75);
        Image::make($image->getRealPath())->resize(300, null, function ($constraint) {
            $constraint->aspectRatio();
        })->save($thumb,75);
      //  $path = Storage::putFile('', $request->file('file')); 
        //$image = Image::make('files/'.$path)->resize(300, 200)->save('files/thumb/'.$path);
        return response()->json(array('status'=>true,'file'=>$filename));
    }
}
